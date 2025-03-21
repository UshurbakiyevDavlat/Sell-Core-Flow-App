<?php

namespace AppModules\Assets\Http\Controllers;

use AppModules\Assets\Http\Requests\CreateAssetRequest;
use AppModules\Assets\Http\Requests\UpdateAssetPriceRequest;
use AppModules\Assets\Http\Requests\UpdateAssetRequest;
use AppModules\Assets\Http\Resources\AssetResource;
use AppModules\Assets\Services\AssetService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

readonly class AssetController
{
    public function __construct(private AssetService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->query('per_page', 10);

        return AssetResource::collection($this->service->getAll($perPage));
    }

    public function store(CreateAssetRequest $request): JsonResponse
    {
        $data = $request->validated();

        return response()->json(new AssetResource($this->service->create($data)), 201);
    }

    public function show(int $id): JsonResponse
    {
        $asset = $this->service->getById($id);
        if (! $asset) {
            throw new NotFoundHttpException();
        }

        return response()->json(new AssetResource($asset));
    }

    public function update(UpdateAssetRequest $request, int $id): Response
    {
        $this->service->update($id, $request->validated());

        return response()->noContent();
    }

    /**
     * @throws Exception
     */
    public function updatePrice(UpdateAssetPriceRequest $request, int $id): Response
    {
        $data = $request->validated();
        $price = $data['price'] ?? null;

        if (! $price) {
            throw new UnprocessableEntityHttpException('Price is required');
        }

        $this->service->updatePrice($id, $price);

        return response()->noContent();
    }

    public function destroy(int $id): Response
    {
        $this->service->delete($id);

        return response()->noContent();
    }
}
