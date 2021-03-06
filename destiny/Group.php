<?php

declare(strict_types=1);

namespace Destiny;

use Carbon\CarbonImmutable;
use Destiny\Definitions\PublicMilestone;
use Destiny\Profile\Progression\ProgressionCollection;
use Illuminate\Support\Arr;

/**
 * Class Group.
 *
 * @property int                  $groupId
 * @property string               $name
 * @property int                  $groupType
 * @property int                  $membershipIdCreated
 * @property string               $creationDate
 * @property string               $modificationDate
 * @property string               $about
 * @property array                $tags
 * @property int                  $memberCount
 * @property bool                 $isPublic
 * @property bool                 $isPublicTopicAdminOnly
 * @property int                  $primaryAlliedGroupId
 * @property string               $motto
 * @property bool                 $allowChat
 * @property bool                 $isDefaultPostPublic
 * @property int                  $chatSecurity
 * @property string               $locale
 * @property int                  $avatarImageIndex
 * @property int                  $homepage
 * @property int                  $membershipOption
 * @property int                  $defaultPublicity
 * @property string               $theme
 * @property string               $bannerPath
 * @property string               $avatarPath
 * @property bool                 $isAllianceOwner
 * @property int                  $conversationId
 * @property bool                 $enableInvitationMessagingForAdmins
 * @property string               $banExpireDate
 * @property array                $features
 * @property array                $clanInfo
 * @property StatisticsCollection $stats
 * @property LeaderboardHandler   $leaderboards
 * @property-read ProgressionCollection $progressions
 * @property-read string $callsign
 * @property-read PublicMilestone $reward
 */
class Group extends Model
{
    public function __construct(array $properties)
    {
        parent::__construct($properties);
    }

    public function loadAll()
    {
        $results = app('destiny')->clanAll($this);

        $this->leaderboards = new LeaderboardHandler($results['leaderboards'] ?? []);
        $this->reward = new PublicMilestone($results['reward'] ?? []);
    }

    public function loadMembers()
    {
        app('destiny')->clanMembers($this);
    }

    public function loadStats()
    {
        $this->stats = app('destiny')->clanStats($this);
    }

    public function loadRewards()
    {
        $this->reward = app('destiny')->clanRewards($this);
    }

    public function loadLeaderboards()
    {
        $this->leaderboards = app('destiny')->clanLeaderboards($this);
    }

    protected function gProgressions()
    {
        $progressions = Arr::get($this->clanInfo, 'd2ClanProgressions', []);

        return new ProgressionCollection($progressions);
    }

    protected function gCallsign()
    {
        return Arr::get($this->clanInfo, 'clanCallsign');
    }

    protected function gCreated(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->creationDate);
    }
}
