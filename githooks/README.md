# Git Hooks

Just copy all file into your `.git/hooks` folder.

Ensure they have permissions to be executed `chmod +x`.

## pre-commit

On the root folder `cp githooks/pre-commit .git/hooks/pre-commit`

Before each commit it'll run the `make fix` & `phing` verification

## pre-push

On the root folder `cp githooks/pre-push .git/hooks/pre-push`

Before each push it'll run the `phing qa` verification

## Copying all to Git Hooks

On the root folder `composer git-hooks`
