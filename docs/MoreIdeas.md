# More Ideas

## Integrating 3.x split-offs
If you have a lot of localization and date(time) operations, you could integrate (cakephp/i18n)[https://github.com/cakephp/i18n] already in your 2.x apps.
This way you can remove all the string and calculation operations around it, and use Time/Carbon class right away.
It will make the upgrade of that functionality not necessary anymore with the major version change.

Similar things could be done with `cakephp/collection`, `cakephp/utility` or any other split-off.
