<?php


namespace App\Base;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as LaravelBaseController;


class BaseController extends LaravelBaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Stores the response object
     *
     * @var BaseResponse
     */
    protected $response;

    public function __construct(BaseResponse $response)
    {
        $this->response = $response;
    }

}