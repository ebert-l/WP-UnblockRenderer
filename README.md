# WP-UnblockRenderer
A WordPress Plugin which radically loads all rescources at once without, blocking the rendering od the site.

## True asynchronous resource Loading in WordPress
Every enqued WordPress Stylesheet is blocking the rendering of the browser. This affects loading times, especially the LCP-Time.

This Plaugin seperates the download of the Styles from the rendering and adds the styles in the moment when the site is fully rendered.
