Thank you for contributing to this project. Please make sure to read the following 2 sections
regarding code style and the git work flow used in this project.

## Code Style
Please keep the following best practices in mind when working on this project:

>Don't use Facades or static methods, use Dependency Injection instead

_Why?_ Better testability, and you won't need to rely on external packages for IDE auto-completion.

> Any database access should be done through Repositories

_Why?_ DRY, better decoupling, plus you will only need to change code in one place when further requirements arise (e.g. exclude all users from queries that have an `is_hidden` flag).

> Repositories should only ever have **one** dependency, and that is the model. Optionally they can inject the Laravel `ConfigRepository` in case they need access to the config.

_Why?_ Repository methods should have one single responsibility. If you depend on multiple models or other repositories for certain methods, there is a good chance that you're doing multiple things at once. Use a service instead.

> Use services for any and all application logic. Services can have as many dependencies as they need. It's fine to depend on multiple repositories or even other services. You should still try to keep them slim, and instead split them out into multiple services once they get too big.

_Why?_ It helps to keep controllers slim, plus it eases onboarding. New developers can check out the application services and generally get a good idea of what the application does. By keeping repositories and services separate, you can guarantee that services will **only** contain application logic and no queries that would ultimately not be that relevant to figuring a project out. Generally, query logic can be figured out by the called repository method names. When seeing `$repository->countReceiptsUnprocessedForDrawing()` in a service, developers will have a general idea what the query does, they don't need to see the entire 7-line query at that point. It's also better for writing tests. Services will have a high priority for unit tests since they contain the critical application logic.

> Use the utilities offered by Laravel where they make sense. For example, use _API Resources_ instead of custom transformers. Make use of Jobs, Events, Listeners, etc. to structure your application. 

## Git Flow
When contributing to the repository, there are a few guidelines you should keep in mind:
- Perform work in a feature branch, branching out from `develop`.
- Never push into develop or master branch. Make a Pull Request. Think of the `develop` branch
as mirroring the Test-Server, and the `master` branch as mirroring the Live-Server.
- Update your local develop branch and do an interactive rebase before pushing your feature and making a Pull Request.

Typical workflow:
```bash
git checkout -b <branchname>
# Perform some work
git add
git commit -m <message>
# Sync with remote to get changes you've missed:
git checkout develop
git pull
# Update your feature branch with latest changes from develop by interactive rebase:
git checkout <branchname>
git rebase -i develop
# If you have conflicts, resolve them, then:
git add <file1> <file2> ...
git rebase --continue
# Push your changes
git push -f
```
Write good commit messages ([source](https://chris.beams.io/posts/git-commit/#seven-rules)):

1. Separate subject from body with a blank line
2. Limit the subject line to 50 characters
3. Capitalize the subject line
4. Do not end the subject line with a period
5. [Use the imperative mood in the subject line](https://chris.beams.io/posts/git-commit/#imperative)
6. [Wrap the body at 72 characters](https://chris.beams.io/posts/git-commit/#wrap-72)
7. Use the body to explain what and why vs. how

Make a pull request! Assign me, and I'll do a code review.
