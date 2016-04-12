<?php

return [

  // layout files in resources/views/themes/<<theme name>>
  // 'cms' is the default
  'theme' => 'cms',

  'secure_host' => null,

  'contact_email' => 'fake@typeblue.com',

  // edit your site at http://yoursite.com/_admin
  'dashboard_directory' => 'admin',

  // content caching on the reader front end
  'enable_cache' => false,
  'cache_minutes' => 2,

  // how many results on a page in the admin listings
  'paginate' => 5,

  // how many blog entries should be listed when you visit the section index
  'blog_posts_per_index' => 20,

  'user_types' => ['user', 'cms_user', 'cms_admin']
];
