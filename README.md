# WP GraphQL CORS

The primary purpose of this plugin is to make the [WP GraphQL](https://github.com/wp-graphql/wp-graphql) plugin authentication "just work". It does this by allowing you set the CORS headers that GraphQL will accept, which means that WordPress's default authentication cookies will be accepted.

This means that if a user is logged into WordPress, they will be able to see things like draft and private pages/posts via GraphQL.

## Features

- Allows WP-GraphQL to accept default WordPress cookies
- Works across multiple domains (so you can have multiple frontends connected to one backend)
- Allows you to filter out specific cookies for added security (perhaps you don't want to use this for WordPress authentication but do want to access other cookies)
- Allows you to customize the GraphQL endpoint
- Extends WP GraphQL to have a logout and login mutation
- Allows you to filter out which cookies are allowed

## Installation

1.  Requires [WP GraphQL](https://github.com/wp-graphql/wp-graphql).
1.  Upload this plugin to WordPress.
1.  Config your GraphQL client (probably Apollo) to include credentials in requests. Generally this is a setting under `httpLinkOptions` and look to set `credentials = "include"`.

Now if the browser is currently logged into WordPress, WP GraphQL will allow access to authenticated data.

## Documentation

If all you want to do is allow draft, private and page previews to be viewed from the frontend, setting `Send site credentials.` and `Add Site Address to "Access-Control-Allow-Origin" header` to true will do this.

You'll also need to config your GraphQL client (probably Apollo) to include credentials in requests. Generally this is a setting under `httpLinkOptions` and look to set `credentials = "include"`.

### Logout Mutation

If enabled in the settings, WP GraphQL will have a new mutation available to allow a user to logout. This is useful if you want to build a "logout" button on a sites frontend.

```
mutation {
    logout(input: {clientMutationId = "anything unique"}){
        clientMutationId
        status
    }
}
```

### Login Mutation

If enabled in the settings, WP GraphQL will have a new mutation available to allow a user to login.

```
mutation {
	loginWithCookies(input: {clientMutationId: "", login: "", password: ""}) {
        clientMutationId
        status
    }
}
```
