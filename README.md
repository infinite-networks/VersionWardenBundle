# InfiniteVersionWardenBundle

A development tool that complains if you are running the wrong version of PHP.

## Installation:

In your project's composer.json, you must set your PHP constraint to a specific minor version of PHP.

```
# composer.json
    "require": {
        "php": "7.1.*",
```

Register the bundle in AppKernel.php for the `dev` and `test` environments only.

## Usage

VersionWardenBundle will automatically detect your expected version by reading composer.lock. No further work is required.

If you run a version of PHP that doesn't match composer.lock, VersionWardenBundle will immediately display an error.

This works in the console and browser.
