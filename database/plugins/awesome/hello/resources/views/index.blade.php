<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hello world from {{ author }} team</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  </head>

  <body>

    <!-- Begin page content -->
    <main role="main" class="container">
      <h1 class="mt-5">Hello World</h1>
      <p class="lead">
        This is the default setting page of the <strong>{{ '{{' }} $plugin->name {{ '}}' }}</strong> plugin!
        <br>
        The page is rendered by <code>\{{ author_class }}\{{ name_class }}\Controllers\DashboardController@index</code>, feel free to replace it with actual content.
      </p>
      <h2>Translation</h2>
      <p class="lead">Below is a phrase retrieved from I18n.<br>If it does not work, you can issue the <code>php artisan translation:upgrade</code> command then refresh this page</p>
      <blockquote>{{ '{{' }} trans('{{ name }}::messages.intro') {{ '}}' }}</blockquote>
      <p class="lead"><strong>IMPORTANT</strong>. The translation file is available at <code>storage/app/plugins/{{ author }}/{{ name }}/resources/lang/en/messages.php</code>.<br>However, remember to execute the <code>artisan</code> command above every time you update the file. <br>Otherwise new translation phrases will not load.</p>

      <h2>Get started</h2>
      <p class="lead">
        You can now now modify this plugin template to add functionality.
      </p>
      <ul>
        <li>Add new routes in <code>./routes.php</code></li>
        <li>Add more actions to this controller file at <code>./src/Controllers/DashboardController.php</code></li>
        <li>Add new pages to the <code>./resources/views/</code> folder.</li>
        <li>Check out the service provider for this plugin at <code>./src/ServiceProvider.php</code> folder.</li>
        <li>Add any PHP file and class you desire to <code>./src</code> folder which follows the PRS-4 convention.<br>For example, add a <code>Sample</code> class in a <code>./src/Sample.php</code> file and access it using the its full namespace of <code>\{{ author_class }}\{{ name_class }}\Sample</code></li>
      </ul>
      <h2>Storage</h2>
      <p class="lead">
        Sometimes a plugin may require users' input or settings. Then you can use the <code>data</code> field of the <code>plugins</code> DB table to store the information.
      </p>
      <pre style="background-color:#efefef"><code>
          $plugin = \Acelle\Model\Plugin::where('name', '{{ plugin }}')->first();
          $plugin->data = json_encode( [ 'plugin data' ] );
          $plugin->save();
        </code></pre>
      <hr />
      <footer>
        <p class="lead">Click <a href="{{ '{{' }} action('\{{ author_class }}\{{ name_class }}\Controllers\DashboardController@index') {{ '}}' }}">here</a> to reload the page. Or <a href="{{ '{{' }} action('Admin\PluginController@index') {{ '}}' }}">click to go back to the Plugin management</a> where you can <strong>enable</strong>, <strong>disable</strong> or <strong>delete</strong> this plugin.</p>
      </footer>

    </main>



  </body>
</html>
