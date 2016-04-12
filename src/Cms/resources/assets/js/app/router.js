import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from './views/dashboard.vue';

import PageIndex from './views/page/index.vue';
import PageSingle from './views/page/edit.vue';

import BlockIndex from './views/block/index.vue';
import BlockSingle from './views/block/edit.vue';

import GalleryIndex from './views/gallery/index.vue';
import GallerySingle from './views/gallery/edit.vue';

import ExtensionIndex from './views/extension/index.vue';
import ExtensionSingle from './views/extension/edit.vue';

import CategoryIndex from './views/category/index.vue';
import CategorySingle from './views/category/edit.vue';

import SectionIndex from './views/section/index.vue';
import SectionSingle from './views/section/edit.vue';

import UserIndex from './views/user/index.vue';
import UserSingle from './views/user/edit.vue';

import NewsFeedIndex from './views/newsfeed/index.vue';
import NewsFeedSingle from './views/newsfeed/edit.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  history: false
});

router.beforeEach(function () {
  window.scrollTo(0, 0)
});

router.map({
  '/': {
    name: 'dashboard',
    component: Dashboard
  },
  '/page': {
    name: 'page-index',
    component: PageIndex
  },
  '/blog': {
    name: 'blog-index',
    component: require('./views/blog/index.vue')
  },
  '/blog/:id': {
    name: 'blog-edit',
    component: require('./views/page/edit.vue')
  },
  '/page/:id': {
    name: 'page-edit',
    component: PageSingle
  },
  '/extension': {
    name: 'extension-index',
    component: ExtensionIndex
  },
  '/extension/:id': {
    name: 'extension-edit',
    component: ExtensionSingle
  },
  '/user': {
    name: 'user-index',
    component: UserIndex
  },
  '/user/:id': {
    name: 'user-edit',
    component: UserSingle
  },  
  '/form': {
    name: 'form-index',
    component: require('./views/form/index.vue')
  },
  '/form/:id': {
    name: 'form-edit',
    component: require('./views/form/edit.vue')
  }, 
  '/form/:id/submissions': {
    name: 'formsubmission-index',
    component: require('./views/formsubmission/index.vue')
  },
  '/asset': {
    name: 'asset-index',
    component: require('./views/asset/index.vue')
  },
  '/upload': {
    name: 'asset-upload',
    component: require('./views/asset/upload.vue')
  },
  '/asset/:id': {
    name: 'asset-edit',
    component: require('./views/asset/edit.vue')
  },   
  '/gallery': {
    name: 'gallery-index',
    component: GalleryIndex
  },
  '/gallery/:id': {
    name: 'gallery-edit',
    component: GallerySingle
  },   
  '/block': {
    name: 'block-index',
    component: BlockIndex
  },
  '/block/:id': {
    name: 'block-edit',
    component: BlockSingle
  },    
  '/category': {
    name: 'category-index',
    component: CategoryIndex
  },
  '/category/:id': {
    name: 'category-edit',
    component: CategorySingle
  },
  '/section': {
    name: 'section-index',
    component: SectionIndex
  },
  '/section/:id': {
    name: 'section-edit',
    component: SectionSingle
  },  
  '/newsfeed': {
    name: 'newsfeed-index',
    component: NewsFeedIndex
  },
  '/newsfeed/:id': {
    name: 'newsfeed-edit',
    component: NewsFeedSingle
  }
});


export default router;
