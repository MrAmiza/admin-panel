<?php

namespace Modules\Permission\Http\Controllers\Api;


use Illuminate\Http\JsonResponse;
use Modules\Permission\Fields\RoleFields;
use Modules\Api\Http\Controllers\ApiController;
use Modules\Permission\Http\Requests\RoleRequest;
use Modules\Permission\Transformers\Role\RoleResource;
use Modules\Permission\Transformers\Role\RoleResourceCollection;

class RoleController extends ApiController
{
    /**
     * get list of Role
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = role()->with('children')
            ->select([
                RoleFields::ID,
                RoleFields::NAME,
                RoleFields::TITLE
            ])
            ->whereNull(RoleFields::PARENT_ID)
            ->get();

        return $this->successResponse(new RoleResourceCollection($roles), __response());
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  RoleRequest  $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $inputs = $request->only($this->getRequestFields());
        // Create a new Role
        $role = role()->create($inputs);
        // Attach the permission IDs to the newly created role
        $role->permissions()->attach($request->input('permissionIds'));

        return $this->successResponse(new RoleResource($role),__response('role','store'));
    }

    /**
     * get Role by id
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $role = role()->find($id);
        return $this->successResponse(new RoleResource($role));
    }

    /**
     * Update Role
     *
     * @param RoleRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(RoleRequest $request, $id): JsonResponse
    {
        $inputs = $request->only($this->getRequestFields());

        $role = role()->update($inputs,$id);
        // Sync the permission IDs to the role
        $role->permissions()->sync($request->input('permissionIds'));

        return $this->successResponse(new RoleResource($role), __response('role','update'));
    }


    /**
     * Delete Role
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $role = role()->deleteRoleAndChildren($id);
        return $this->successResponse(new RoleResource($role),__response('role','destroy'));
    }

    /**
     * Get Request Fields
     *
     * @return array
     */
    private function getRequestFields(): array
    {
        return [
            RoleFields::NAME,
            RoleFields::TITLE,
            RoleFields::PARENT_ID
        ];
    }

}
