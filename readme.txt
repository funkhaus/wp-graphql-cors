=== WPGraphQL CORS ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://example.com/
Tags: comments, spam
Requires at least: 4.5
Tested up to: 5.1.1
Stable tag: 0.1.0
License: GPLv3
License URI: https://opensource.org/licenses/gpl-3.0 GNU General Public License

Allows user to customize WPGraphQL request CORS Headers and response cookie header.

== Description ==

This is the long description.  No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

*   "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

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
