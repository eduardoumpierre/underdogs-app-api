<?php

namespace App\Repositories;

use App\Bill;
use App\User;
use App\Interfaces\UserInterface;
use Dreamonkey\OneSignal\OneSignalClient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    private $levelRepository;
    private $userDropRepository;
    private $userBadgeRepository;
    private $userAchievementRepository;

    /**
     * UserRepository constructor.
     * @param LevelRepository $lr
     * @param UserDropRepository $udr
     * @param UserAchievementRepository $uar
     * @param UserBadgeRepository $ubr
     */
    public function __construct(LevelRepository $lr, UserDropRepository $udr, UserAchievementRepository $uar,
                                UserBadgeRepository $ubr)
    {
        $this->levelRepository = $lr;
        $this->userDropRepository = $udr;
        $this->userBadgeRepository = $ubr;
        $this->userAchievementRepository = $uar;
    }


    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return User::query()->get();
    }

    /**
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public function findAllWithActiveStatus()
    {
        return User::query()
            ->from('users AS u')
            ->select(['u.id', 'u.name', 'u.cpf', DB::raw('COALESCE((SELECT is_active FROM bills WHERE users_id = u.id ORDER BY is_active DESC LIMIT 1), 0) AS is_active')])
            ->orderBy('is_active')
            ->get();
    }

    /**
     * @return Collection|static[]
     */
    public function findAllOnline()
    {
        return User::query()
            ->from('users AS u')
            ->select(['u.id', 'u.name', 'u.cpf'])
            ->where(DB::raw('(SELECT is_active FROM bills WHERE users_id = u.id ORDER BY is_active DESC LIMIT 1)'), '=', true)
            ->get();
    }

    /**
     * @return Model|static
     */
    public function findOnlineUsersStats()
    {
        $billTotal = DB::raw('(SELECT SUM(p.price) FROM bills_products AS bp JOIN products AS p ON bp.products_id = p.id WHERE bp.bills_id IN (SELECT b.id FROM bills AS b WHERE b.is_active = TRUE)) AS total');

        return User::query()
            ->from('users AS u')
            ->select([DB::raw('COUNT(u.id) AS quantity'), $billTotal])
            ->where(DB::raw('(SELECT is_active FROM bills WHERE users_id = u.id ORDER BY is_active DESC LIMIT 1)'), '=', true)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        if (['*'] === $columns) {
            $user = User::query()->findOrFail($id, $columns);
            $user['current_level'] = $this->levelRepository->findOneById($user->levels_id);
            $user['next_level'] = $this->levelRepository->findOneByNumber($user['current_level']['number'] + 1);

            return $user;
        }

        return User::query()->findOrFail($id, $columns);
    }

    /**
     * @param array $params
     * @param bool $isQuickInsert
     * @return Model
     */
    public function create(array $params, bool $isQuickInsert = false): Model
    {
        $params['experience'] = 0;
        $params['levels_id'] = 1;

        if (!isset($params['role'])) {
            $params['role'] = 0;
        }

        if (!$isQuickInsert) {
            $params['password'] = app('hash')->make($params['password']);
        }

        return User::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = User::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        User::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * Adds experience to user
     *
     * @param int $id
     * @param int $experience
     * @return Model
     * @throws \Dreamonkey\OneSignal\OneSignalException
     */
    public function addExperience(int $id, int $experience)
    {
        $user = $this->findOneById($id, ['id', 'experience', 'levels_id']);
        $user->levels_id = $this->updateUserLevel($user->id, $user->levels_id, $user->experience, $experience);
        $user->experience += $experience;
        $user->update();

        return $user;
    }

    /**
     * Returns the new user level
     *
     * @param int $userId
     * @param int $currentLevel
     * @param int $currentExperience
     * @param int $newExperience
     * @return int
     * @throws \Dreamonkey\OneSignal\OneSignalException
     */
    private function updateUserLevel(int $userId, int $currentLevel, int $currentExperience, int $newExperience)
    {
        $levels = $this->levelRepository->findNextLevelsByExperience($currentLevel, $currentExperience, $newExperience);

        if (count($levels) > 0) {
            foreach ($levels as $key => $val) {
                // @todo Adds record to history

                // Adds a new drop from level's drops
                $this->userDropRepository->create(['user' => $userId, 'level' => $val['number']]);
            }

            $client = new OneSignalClient(env('ONESIGNAL_APP_ID'), env('ONESIGNAL_REST_API_KEY'), env('ONESIGNAL_USER_AUTH_KEY'));
            $client->postNotification([
                'tags' => [['key' => 'user_id', 'relation' => '=', 'value' => $userId]],
                'contents' => ['en' => 'Veja a sua nova recompensa.'],
                'headings' => ['en' => 'Você subiu de nível!'],
                'data' => ['level' => $levels->last()->number]
            ], env('ONESIGNAL_APP_ID'));

            return $levels->last()->id;
        }

        return $currentLevel;
    }

    /**
     * @return Collection|static[]
     */
    public function findRanking()
    {
        return User::query()->from('users AS u')
            ->select(['u.id', 'u.username', 'l.number'])
            ->join('levels AS l', 'l.id', '=', 'u.levels_id')
            ->where('u.username', '!=', '')
            ->whereNotNull('u.username')
            ->orderByDesc('u.experience')
            ->get();
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findAchievementsByUserId(int $id)
    {
        return $this->userAchievementRepository->findAllByUserId($id);
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findDropsByUserId(int $id)
    {
        return $this->userDropRepository->findAllByUserId($id);
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findBadgesByUserId(int $id)
    {
        return $this->userBadgeRepository->findAllByUserId($id);
    }

    /**
     * @param $facebookId
     * @return Collection|Model|static|static[]
     */
    public function findOneByFacebookId(int $facebookId)
    {
        return User::query()->where('facebook_id', '=', $facebookId)->whereNotNull('cpf')->firstOrFail();
    }
}
