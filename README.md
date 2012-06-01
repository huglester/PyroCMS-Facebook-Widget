PyroCMS Facebook Widget
=======================

This PyroCMS widget allows you to display facebook posts from a user or page in a widget area such as a sidebar, you may have multiple instances of the widget which use the same cached feed.

### Licence

You can use the code in this repository how ever you like, non-commercial or commercial, however I will not be held accountable for any issues the code may cause.
I would appreciate attribution if you use the code.

### Usage

The widget should be installed in one of the following locations:

 * The /addons/[site-ref]/widgets/facebook
 * The /addons/shared_addons/widgets/facebook

Once you have uploaded the *facebook* folder, navigate to your **PyroCMS backend**, and to **Content** > **Widgets**

Add the widget by dragging the **Facebook Feed** widget into the widget area of your choice, you will then see a form in which you need to enter the following details:

 * Facebook app ID
 * Facebook app secret key
 * Username
 * Number of items

#### Facebook Application

You must have a registered facebook application - which will give you an application ID and key. To create a facebook application visit [Facebook Apps] [1] and click **Create New App** you will then be asked to enter a name and namespace (these are not really important in this case) you will then see the **App ID/API Key** and **App secret**.

### Improvements

The code currently uses [PyroCache] [2] for caching the posts, this is file based, and could be recoded to use the [CodeIgniter Cache] [3] which allows APC and Memcached as well as file based fallback.

### Credits

Me, [Joel Vardy] [4] for writing the code.

  [1]: https://developers.facebook.com/apps     "Facebook Apps"
  [2]: http://docs.pyrocms.com/2.1/manual/index.php/developers/tools/pyrocache     "PyroCache"
  [3]: http://codeigniter.com/user_guide/libraries/caching.html     "CodeIgniter Cache"
  [4]: http://joelvardy.com     "Joel Vardy"