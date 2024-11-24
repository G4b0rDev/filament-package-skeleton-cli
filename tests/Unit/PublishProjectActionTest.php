<?php

use App\Actions\PublishProjectAction;
use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    Config::swap(Mockery::mock('config'));
    File::swap(Mockery::mock('file'));
    Process::swap(Mockery::mock('process'));
});

it('should create a new project from the config path', function () {
    Config::shouldReceive('get')
        ->with('path')
        ->andReturn('/base/path');

    File::shouldReceive('exists')
        ->with('/base/path/project-name')
        ->andReturnFalse();

    File::shouldReceive('ensureDirectoryExists')
        ->with('/base/path/project-name');

    Process::shouldReceive('run')
        ->with('git clone --depth 1 git@github.com:G4b0rDev/filament-package-skeleton.git /base/path/project-name');

    Process::shouldReceive('path')
        ->with('/base/path/project-name')
        ->andReturnSelf();

    Process::shouldReceive('run')
        ->with('rm -rf /base/path/project-name/.git');

    Process::shouldReceive('run')
        ->with('cd /base/path/project-name && git init && git branch -M main');

    (new PublishProjectAction)('project-name');

    File::swap(Mockery::mock('file'));

    File::shouldReceive('exists')
        ->with('/base/path/project-name')
        ->andReturnTrue();

    expect(File::exists('/base/path/project-name'))->toBeTrue();
});

it('should create a new project into the current directory', function () {
    Config::shouldReceive('get')
        ->with('path')
        ->andReturn(null);

    $currentDir = getcwd();

    File::shouldReceive('exists')
        ->with("{$currentDir}/project-name")
        ->andReturnFalse();

    File::shouldReceive('ensureDirectoryExists')
        ->with("{$currentDir}/project-name");

    Process::shouldReceive('run')
        ->with("git clone --depth 1 git@github.com:G4b0rDev/filament-package-skeleton.git {$currentDir}/project-name");

    Process::shouldReceive('path')
        ->with("{$currentDir}/project-name")
        ->andReturnSelf();

    Process::shouldReceive('run')
        ->with("rm -rf {$currentDir}/project-name/.git");

    Process::shouldReceive('run')
        ->with("cd {$currentDir}/project-name && git init && git branch -M main");

    (new PublishProjectAction)('project-name');

    File::swap(Mockery::mock('file'));

    File::shouldReceive('exists')
        ->with("{$currentDir}/project-name")
        ->andReturnTrue();

    expect(File::exists("{$currentDir}/project-name"))->toBeTrue();
});

it('should throw an exception if the project already exists', function () {
    Config::shouldReceive('get')
        ->with('path')
        ->andReturn('/base/path');

    File::shouldReceive('exists')
        ->with('/base/path/project-name')
        ->andReturnTrue();

    (new PublishProjectAction)('project-name');
})->throws(ProjectAlreadyExistsException::class);
