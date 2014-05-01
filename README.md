TechDivision_RewriteModule
==========================

[![Latest Stable Version](https://poser.pugx.org/techdivision/rewritemodule/v/stable.png)](https://packagist.org/packages/techdivision/rewritemodule) [![Total Downloads](https://poser.pugx.org/techdivision/rewritemodule/downloads.png)](https://packagist.org/packages/techdivision/rewritemodule) [![Latest Unstable Version](https://poser.pugx.org/techdivision/rewritemodule/v/unstable.png)](https://packagist.org/packages/techdivision/rewritemodule) [![License](https://poser.pugx.org/techdivision/rewritemodule/license.png)](https://packagist.org/packages/techdivision/rewritemodule) [![Build Status](https://travis-ci.org/techdivision/TechDivision_RewriteModule.png)](https://travis-ci.org/techdivision/TechDivision_RewriteModule)

What is TechDivision_RewriteModule?
-----------------

A simple rewrite module for PHP based web servers which uses a self made structure for usable rules.
It can be used similar to [`Apaches mod_rewrite`](<http://httpd.apache.org/docs/current/mod/mod_rewrite.html>) and
provides rewriting and redirecting capabilities.

Usage
-----------------
The module can be used according to the `\TechDivision\WebServer\Interfaces\ModuleInterface` interface.
It needs an initial call of the `init` method and will process any request offered to the `process` method.
The module is best used within the [`TechDivision_WebServer`](<https://github.com/techdivision/TechDivision_WebServer>)
project as it offers all needed infrastructure.

Rules
-----------------
Most important part of the module is the way in which it can perform rewrites. All rewrites are based on rewrite rules
which consist of three important parts:

- *condition string* : Conditions which have to be met in order for the rule to take effect. See more [down here](<#condition-syntax>)

- *target string* : The target to rewrite the requested URI to. Within this string you can use backreferences similar
    to the Apache mod_rewrite module with the difference that you have to use the `$ syntax`
    (instead of the `$/%/%{} syntax` of Apache).
    Backreferences are parts of the matching rule conditions which you specifically pick out via regex.

    *Simple example* : A condition like `(.+)@$X_REQUEST_URI` would produce a back reference `$1` with the value `/index`
        for a requested URI `/index`. The target string `$1/welcome.html` would therefore result in a rewrite to `/index/welcome.html`

- *flag string* : You can use flags similar to mod_rewrite which are used to make rules react in a certain way or
    influence further processing. See more [down here](<#flags>)

Condition Syntax
-----------------
The Syntax of possible conditions is roughly based on the possibilities of Apache's RewriteCondition and RewriteRule
combined.
To make use of such a combination you can chain conditions together using the `|` symbol for OR-combined, and the `,`
character for AND-combined conditions.
Please be aware that AND takes precedence over OR!
Conditions can either be PCRE regex or certain fixed expressions.
So a condition string of `([A-Z]+\.txt)|^/([0-9]+),-f` would match only real files (through `-f`) which either begin
with numbers or end with capital letters and the extension .txt.
As you might have noticed: Backslashes do **not have to be escaped**.

You might also be curious of the `-f` condition.
This is a direct copy of Apaches -f RewriteCondition.
We also support several other expressions to regex based conditions which are:

 - *<<COMPARE_STRING>* : Is the operand lexically preceding `<COMPARE_STRING>`?
 - *><COMPARE_STRING>* : Is the operand lexically following `<COMPARE_STRING>`?
 - *=<COMPARE_STRING>* : Is the operand lexically equal to `<COMPARE_STRING>`?
 - *-d* : Is the operand a directory?
 - *-f* : Is the operand a real file?
 - *-s* : Is the operand a real file of a size greater than 0?
 - *-l* : Is the operand a symbolic link?
 - *-x* : Is the operand an executable file?

If you are wondering what the `operand` might be: it is **whatever you want it to be**!
You can specify any operand you like using the `@` symbol.
All conditions within a rule will use the next operand to their right and if none is given the requested URI.
For example:

- *`([A-Z]+\.txt)|^/([0-9]+)`* Will take the requested URI for both conditions (note the `|` symbol)
- *`([A-Z]+\.txt)|^/([0-9]+)@$DOCUMENT_ROOT`* Will test both conditions against the document root
- *`([A-Z]+\.txt)@$DOCUMENT_ROOT|^/([0-9]+)`* Will only test the first one against the document root and the second against the requested URI

You might have noted the `$` symbol before `DOCUMENT_ROOT` and remembered it from the backreference syntax.
That's because all Apache common server vars can be explicitly used as backreferences too!

That does not work for you? Need the exact opposite? No problem!

All conditions, weather regex or expression based can be negated using the `!` symbol in front of them!
So `!^([0-9]+)` would match all strings which do NOT begin with a number and `!-d` would match all non-directories.

Flags
-----------------
Flags are used to further influence processing.
You can specify as many flags per rewrite as you like, but be aware of their impact!
Syntax for several flags is simple: just separate them with a `,` symbol.
Flags which might accept a parameter can be assigned one by using the `=` symbol.
Currently supported flags are:

- *L* : As rules are normally processed one after the other, the `L` flag will make the flagged rule the last one processed
    if matched.

- *R* : If this flag is set and the `target string` is a valid URL we will make a redirect instead of a rewrite

- *M* : Stands for map. Using this flag you can specify an external source (have a look at the Injector classes of the WebServer project) of a target map.
    With `M=<MY_BACKREFERENCE>` you can specify what the map's index has to match. This matching is done **only** if the rewrite condition matches and will behave as another condition