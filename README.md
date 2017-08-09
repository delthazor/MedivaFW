# MedivaFW
Just a simple web framework for myself to make simple web page creation easier

From time to time my friends, family, and other people ask me for more or less simple web pages mostly for home use, 
or for small communities like private mmo serves or clans or so.

To help myself with creating such pages I created this framework. 

Why I did it? 
Mostly because the available framweorks out there are mostly too big and heavy (yes even the 'lightweight' ones) 
for such simple pages. 
Also I do not like to use codes that I didn't understand fully, unless it is absolutely necessary, and I don't have the time to
read through and understand a lot of codes in an existing framework.
Another point is that I hate javascript (and everything that builds on or has connections to javascript)
and most frameworks out there either has some built in javascript, or plugins provided for them tend to have it in some form. 
However, I like PHP (yes, even if countless people consider it bad) so I wanted a pure PHP framework for making my job easier.

So I started working out the basics of a modular/oop PHP framework that suits my taste.
In the meantime it was also used for my Bsc thesis (the name of the framework originates from the 
medival style turnased browser game I made with tis framework as part of my thesis)
After a lot of work and upgrade on the system, and I started to feel the need of version control, thus it ended up on github
fo convenience.

If you have similar concerns and mindset, you can use this framework freely, I'll explain the workings later, but first
I would like to point out what this framework is not:
 - Itis not for commercial use
 - It is not highly secure
 - It is not particalrly efficient

Since it is intended for creating small pages that are usually visited by a small number of people often from a closed group, 
it is not designed for and thus not allowed for commercial use by any means.
Also for the same reason it is not higly secure. Although it has some built-in security features, 
but it is far not enough for public use, nor it is indentded to be. Yo are free to write new security modules, 
and if you are so kind to open a pull request for it I will review and incorporate if I see fit, but I will not improve the
security features of the system on my own, unless on time I'll have to make a page where it is absolutely necessary to do so.
And for the vey same reason again, it is not particularly efficient. 
However, when I have the time to think through and improve this, I do but I don't consider the framework any more efficient 
than anything out there, especially not in case of performance heavy pages. They were not intended to be created by this FW.

# So how the framework works?

The basic principle is that, in the html sources there are special token words.
The PHP system parses up the html sources, recognizes the tokens and sends them to the token handlers.
The token handlers then replace the tokens with dynamically generated content (that can also contain more tokens)
that is dependent of the current state (config, contents of the post message, contents of sesson variable, database, etc.)

The handlers themselves are basically subscribers for the tokens. Each handler is basicaly a class that subscribes one if its 
member functions for a token. One class can subscribe for multiple tokens, but it is recommended to keep in mind the
Single Responsibility Principle and the Open-Closed principle. So to put it short a handler class should only subscribe for
the tokens it really needs to handle, and should not impact any other part of the system that would require it to change.
The only exception currently is the redirection handling which is of course subject to a change, whenever a handler introduces
functionality that needs the next target page to be other then the default one.
In this case the redirection handling could redirect the rendered page to be the one needed, instead the one the system tries
to fall back to.

And basically that's it!

Of course the system has some helper functions, like getting a html content, or encrypting a text, and likes of those.
It even has a quite interesting sql interface I added when I switched mysql to mysqli commands, and one time it had a file
wirter implementation also (for a little page where no database was available), but I got rid of it some time ago.
Also I like to have my database object as a singleton, so database conenctions remain at minimal.

I think the code is relatively simple if you understand the above logic, so if you wanna use it, explore it first.
