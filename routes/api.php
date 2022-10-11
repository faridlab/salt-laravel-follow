<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use SaltFollow\Controllers\ApiNestedResourcesController;

use SaltFollow\Controllers\FollowsResourcesController;

$version = config('app.API_VERSION', 'v1');

Route::middleware(['api'])
    ->prefix("api/{$version}")
    ->group(function () {

    // API: FOLLOWS RESOURCES
    // ALIAS ROUTE
    Route::post("follow/{id}", [FollowsResourcesController::class, 'follow']); // create new collection
    Route::delete("unfollow/{id}", [FollowsResourcesController::class, 'destroy'])->where('id', '[a-zA-Z0-9-]+'); // soft delete a collection by ID
    Route::get("following", [FollowsResourcesController::class, 'following']); // get entire following
    Route::get("followers", [FollowsResourcesController::class, 'followers']); // get entire followers

    Route::get("follows", [FollowsResourcesController::class, 'index']); // get entire collection
    Route::post("follows", [FollowsResourcesController::class, 'store']); // create new collection

    Route::get("follows/trash", [FollowsResourcesController::class, 'trash']); // trash of collection

    Route::post("follows/import", [FollowsResourcesController::class, 'import']); // import collection from external
    Route::post("follows/export", [FollowsResourcesController::class, 'export']); // export entire collection
    Route::get("follows/report", [FollowsResourcesController::class, 'report']); // report collection

    Route::get("follows/{id}/trashed", [FollowsResourcesController::class, 'trashed'])->where('id', '[a-zA-Z0-9-]+'); // get collection by ID from trash

    // RESTORE data by ID (id), selected IDs (selected), and All data (all)
    Route::post("follows/{id}/restore", [FollowsResourcesController::class, 'restore'])->where('id', '[a-zA-Z0-9-]+'); // restore collection by ID

    // DELETE data by ID (id), selected IDs (selected), and All data (all)
    Route::delete("follows/{id}/delete", [FollowsResourcesController::class, 'delete'])->where('id', '[a-zA-Z0-9-]+'); // hard delete collection by ID

    Route::get("follows/{id}", [FollowsResourcesController::class, 'show'])->where('id', '[a-zA-Z0-9-]+'); // get collection by ID
    Route::put("follows/{id}", [FollowsResourcesController::class, 'update'])->where('id', '[a-zA-Z0-9-]+'); // update collection by ID
    Route::patch("follows/{id}", [FollowsResourcesController::class, 'patch'])->where('id', '[a-zA-Z0-9-]+'); // patch collection by ID
    // DESTROY data by ID (id), selected IDs (selected), and All data (all)
    Route::delete("follows/{id}", [FollowsResourcesController::class, 'destroy'])->where('id', '[a-zA-Z0-9-]+'); // soft delete a collection by ID

});
