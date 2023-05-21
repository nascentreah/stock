<?php

namespace App\Services;

use App\Events\AfterUserJoinedCompetition;
use App\Events\BeforeUserJoinedCompetition;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Carbon;

class CompetitionService
{
    private $userModel;
    private $competition;

    public function __construct(Competition $competition)
    {
        $this->competition = $competition;

        // It's important to resolve user model to a particular class (App\Models\Users or Packages\Accounting\Models\User if the paid competitions add-on is installed).
        $this->userModel = resolve(User::class);
    }

    /**
     * User to join the competition
     *
     * @param User $user
     */
    public function join(User $user)
    {
        event(new BeforeUserJoinedCompetition($this->competition, $user));

        // create participant
        $this->competition->participants()->attach($user->id, [
            'start_balance'     => $this->competition->start_balance,
            'current_balance'   => $this->competition->start_balance
        ]);

        $updateTimestamps = FALSE;
        $this->competition->slots_taken++; // increment slots taken

        // minimum number of participants joined => start competition
        if ($this->competition->slots_taken >= $this->competition->slots_required && !$this->competition->start_time && !$this->competition->end_time && $this->competition->status != Competition::STATUS_IN_PROGRESS) {
            $now = Carbon::now();
            $durationInterval = new \DateInterval($this->competition->duration);
            $this->competition->start_time = $now;
            $this->competition->end_time = $now->add($durationInterval);
            $this->competition->status = Competition::STATUS_IN_PROGRESS;
            $updateTimestamps = TRUE;
        }

        // update competition
        $this->competition->save(['timestamps' => $updateTimestamps]);

        event(new AfterUserJoinedCompetition($this->competition, $user));
    }

    /**
     * User to leave a given competition
     *
     * @param User $user
     */
    public function leave(User $user)
    {
        $this->competition->participants()->detach($user->id);

        $updateTimestamps = FALSE;
        $this->competition->slots_taken--; // decrement slots taken

        // minimum number of participants joined => start competition
        if ($this->competition->slots_taken < $this->competition->slots_required && $this->competition->status == Competition::STATUS_IN_PROGRESS) {
            $this->competition->start_time = NULL;
            $this->competition->end_time = NULL;
            $this->competition->status = Competition::STATUS_OPEN;
            $updateTimestamps = TRUE;
        }

        // update competition
        $this->competition->save(['timestamps' => $updateTimestamps]);

        // delete all trades of this user in the competition
        $this->competition->trades()->where('user_id', $user->id)->delete();
    }

    /**
     * Add bots to the competition
     *
     * @param int $botsCount
     */
    public function addBots(int $botsCount)
    {
//        $user = resolve('App\Models\User');

        $bots = $this->userModel::where('role', User::ROLE_BOT)
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotIn('id', $this->competition->participants()->pluck('user_id')->all())
            ->inRandomOrder()
            ->limit($botsCount)
            ->get();

        foreach ($bots as $bot) {
            $this->join($bot);
        }
    }

    /**
     * Remove bots from the competition
     *
     * @param int $botsCount
     */
    public function removeBots(int $botsCount)
    {
        $bots = $this->competition->participants()->where('role', User::ROLE_BOT)->inRandomOrder()->limit($botsCount)->get();

        foreach ($bots as $bot) {
            $this->leave($bot);
        }
    }

    public function countBots()
    {
        return $this->competition->participants()->where('role', User::ROLE_BOT)->count();
    }

    public function clone()
    {
        $clonedCompetition = $this->competition->replicate();
        $clonedCompetition->status = Competition::STATUS_OPEN;
        $clonedCompetition->slots_taken = 0;
        $clonedCompetition->start_time = NULL;
        $clonedCompetition->end_time = NULL;
        $clonedCompetition->user()->associate($this->competition->user);
        $clonedCompetition->save(); // in case any of model relationships are also updated push() method needs to be used

        // add the same number of bots if they existed in the cloned competition
        if ($botsCount = $this->countBots())
            (new self($clonedCompetition))->addBots($botsCount);
    }
}