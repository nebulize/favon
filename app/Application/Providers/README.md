### Providers

This folder includes all service providers to bootstrap the application. This includes routes,
event listeners and view composers.

In the `AppServiceProvider` we register the adapters for our external API interfaces, as singletons.
This is done so that we can enforce rate limiting on all requests, no matter what part of the application
they come from, in order to not exceed the limits set by the APIs.

