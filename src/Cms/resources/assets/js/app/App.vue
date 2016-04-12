<template>
  <div>
    <nav class="navbar navbar-light bg-faded">
      <div class="container">
        <a class="navbar-brand" href="#"><i class="fa fa-home"></i></a>
        <ul class="nav navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Site</a>
            <div class="dropdown-menu" aria-labelledby="Preview">
              <a class="dropdown-item" v-link="{ name: 'page-index', params: {}}">Pages</a>
              <a class="dropdown-item" v-link="{ name: 'blog-index', params: {}}">Blogs</a>
              <a class="dropdown-item disabled">Landings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" v-link="{ name: 'gallery-index', params: {}}">Galleries</a>
              <a class="dropdown-item" v-link="{ name: 'form-index', params: {}}">Forms</a>
              <a class="dropdown-item" v-link="{ name: 'block-index', params: {}}">Blocks</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item disabled">Menus</a> 
              <a class="dropdown-item disabled">Layouts</a>              
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" v-link="{ name: 'newsfeed-index', params: {}}">News Feed</a>
              <a class="dropdown-item disabled">Promos</a>
              <a class="dropdown-item disabled">Events</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Assets</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" v-link="{ name: 'asset-index', params: {}}">Images</a>
              <a class="dropdown-item disabled">Videos</a>
              <a class="dropdown-item disabled">Documents</a>
              <a class="dropdown-item disabled">Resources</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Data </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" v-link="{ name: 'form-index', params: {}}">Form Results</a>
              <a class="dropdown-item disabled">Poll Results</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">E-Com </a>
            <div class="dropdown-menu">
              <a class="dropdown-item disabled">Products</a>
              <a class="dropdown-item disabled">Orders</a>
              <a class="dropdown-item disabled">Inventory</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CRM</a>
            <div class="dropdown-menu">
              <a class="dropdown-item disabled">Customers</a>
              <a class="dropdown-item disabled">Support</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#!/category">Categories</a>
              <a class="dropdown-item" href="#!/section">Site Sections</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#!/user">CMS Users</a>
              <a class="dropdown-item" href="#!/extension">Extensions</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item disabled">Permissions</a>
              <a class="dropdown-item disabled">E-commerce</a>
              <a class="dropdown-item disabled">Responses</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/logout">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <br /><br />

    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Store from './vuex/store';

export default {
  Store
}
</script>