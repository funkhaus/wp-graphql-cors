=== WPGraphQL CORS ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://example.com/
Tags: comments, spam
Requires at least: 4.5
Tested up to: 5.4.2
Stable tag: 1.1.0
License: GPLv3
License URI: https://opensource.org/licenses/gpl-3.0 GNU General Public License

Allows user to customize WPGraphQL request CORS Headers and response cookie header.

== Description ==

WPGraphQL CORS comes with a few options that can be changed from the **WPGraphQL CORS** settings
page under the **Settings** menu on the WordPress dashboard. These settings and the usage are as follows.
- `Access-Control-Allow-Origin`: The option allows the user to set allowed domain that can access the GraphQL
server as well as any credentials such as cookies that the current user may have on the WordPress installation.
- `Include site address in Access-Control-Allow-Origin`: This will cause the `Site Address` to be included in
`Access-Control-Allow-Origin`
- `Filter WPGraphQL response cookies`: Using regular expression specific cookies can be targeted for inclusion
in the GraphQL request.
- `Add logout mutation for destroying user session`: Will add a `logout` mutation to the GraphQL schema that can
be used to destroy the user session and credentials.
- `GraphQL endpoint`: Allows the user to change the name of the GraphQL endpoint.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

0. Install and activate **[WPGraphQL](https://github.com/wp-graphql/wp-graphql)** 
1. Install and activate **WPGraphQL CORS**

== Frequently Asked Questions ==

= Can this be used to pass cookies to a Next/Apollo or Nuxt/Apollo application =

Yes, the Apollo client configuration and WPGraphQL CORS settings just have to be set accordingly.
See and example Next/Apollo configuration below
```
import { withData } from "next-apollo";
import { HttpLink } from "apollo-link-http";

const httpLink = new HttpLink({
  uri: "http://localhost/wordpress/graphql",
  credentials: "include"
});

const createClient = {
  link: httpLink,
  request: operation => {
    operation.setContext({
      fetchOptions: {
        credentials: "include"
      },
    });
  }
};

export default withData(createClient);
```

= What about foo bar? =

Answer to foo bar dilemma.
