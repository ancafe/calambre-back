<?php

namespace App\Jobs;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Jobs\Middleware\RateLimiterForEdis;
use App\Models\Contract;
use App\Models\EdisInfo;
use App\Models\Supply;
use App\Models\User;
use App\Services\Edis\EdisService;
use App\Services\Measure\StorageMeasureService;
use Edistribucion\EdisClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadMeasureFromEDISAndStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Contract $contract;
    protected Supply $supply;
    protected \DateTime $startDate;
    protected \DateTime $endDate;
    protected EdisService $edisService;
    protected StorageMeasureService $storageService;
    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Contract $contract,
        Supply $supply,
        ?\DateTime $startDate = null,
        ?\DateTime $endDate = null
    )
    {
        $this->user = $user->withoutRelations();
        $this->contract = $contract->withoutRelations();
        $this->supply = $supply->withoutRelations();
        $this->startDate = $startDate;
        $this->endDate = $endDate;

    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Edistribucion\EdisError|ApiError
     */
    public function handle(StorageMeasureService $storageService)
    {
        $this->storageService = $storageService;

        $this->edisService = new EdisService(
            new EdisClient(
                $this->user->edis->username,
                $this->user->edis->password,
            )
        );

        $measures = null;
        if ($this->startDate <= $this->endDate) {
            $measures = $this->edisService->getMeasureInterval($this->contract->atrId, $this->startDate, $this->endDate);
        } else {
            $measures = $this->edisService->getMeasure($this->contract->atrId);
        }

        if ($measures){
            $this->storageService->storage($measures, $this->user, $this->supply);
        } else {
            throw new ApiError([ErrorDtoFactory::noMeasureFounded()]);
        }
    }

    public function middleware()
    {
        return [new RateLimiterForEdis];
    }
}
