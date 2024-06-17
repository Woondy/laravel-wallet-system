<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected $status;
    protected $message;

    public function __construct(bool $status = true, string $message = null, $resource = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $this->formatResource($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data ?? [],
        ];
    }

    protected function formatResource($resource)
    {
        if ($resource === null) {
            return null;
        }

        if ($resource instanceof JsonResource) {
            return $resource->toArray(request());
        }

        return $resource;
    }

    public static function success(string $message = null, $resource = null, $statusCode = 200)
    {
        return response()->json(new static(true, $message, $resource), $statusCode);
    }

    public static function error(string $message = null, $resource = null, $statusCode = 400)
    {
        return response()->json(new static(false, $message, $resource), $statusCode);
    }
}
