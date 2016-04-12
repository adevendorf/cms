<template>
<div class="content">

  <div class="card" v-show="feed && items">
    <div class="card-block">
      <div class="row">
        <div class="col-sm-3" v-show="feed">
          <h4>{{feed.name}} Feed</h4>
          <hr />
          <a @click="openLister" class="btn btn-primary">Add Headline</a>
        </div>
        <div class="col-sm-6"></div>        
        <div class="col-sm-3 text-xs-right">
          <a @click="expandAll" class='btn btn-default'><i class="fa fa-expand"></i></a> 
          <a @click="hideAll" class='btn btn-default'><i class="fa fa-compress"></i></a> 
        </div>
      </div>

    </div>
  </div>

  <div v-if="items.length">
    <ul v-bind:id="'sortable-' + id" class="will-sort">
      <li v-for="item in items" v-bind:data-id="item.id">
        <div 
          v-if="item.id" 
          class="handle">         
          <div class="handle-holder"></div>
          <i class="fa fa-arrows"></i>
        </div>

        <div v-if="!item.type">
          <newsitem :content.sync="item"></newsitem>
        </div>
      </li>
    </ul>
  </div>

  <modal size="large" :show.sync="modalVisible" header="true" footer="true">
    <div slot="header">
      Select a Headline
    </div>
    <div slot="body">   
      <lister :parent-id="id"></lister>     
    </div>
    <div slot="footer">
      <button class="btn btn-default" @click="modalVisible = false">Cancel</button>
    </div>
  </modal>
</div>
</template>

<script>

import lister from '../../components/page/lister.vue';
import newsitem from '../../components/news/newsitem.vue';


export default {
  data() {
    return{
      resource: null,
      id: null,
      feed: false,
      items: [],
      selectorOptions: {
        parentType: 'newsfeed'
      },
      drake: null,
      modalVisible: false
    }
  },

  components: {
    newsitem,
    lister
  },

  methods: {
    openLister() {
      this.modalVisible = !this.modalVisible;
    },

    expandAll() {
      this.$broadcast('feed::expandAll');
    },

    hideAll() {
      this.$broadcast('feed::hideAll');
    },

    addItem(data) {
      var obj = {
        section_id: parseInt(this.id),
        page_id: data.id
      };
      this.resource.save(obj).then((response) => {
        this.items.unshift(response.data);
        setTimeout(this.saveOrder,0);
      });
    }, 

    getItem: function() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('feed', response.data.feed)
        this.$set('items', response.data.items);
        setTimeout(this.setUpDragging, 0);
      });
    },     
  
    saveOrder() {
      var order = [];

      this.setOrder();

      this.items.forEach((val) => {
        order.push({
          id: val.id,
          order: val.order
        });
      })

      this.$http.post('newsitem/order', {items: order}).then((response) => {
      });
    },


    setOrder() {
      var index = 0;
      _.forEach($('#sortable-' + this.id + ' li'), (item) => {
        var id = parseInt($(item).attr('data-id'));        
        var obj = _.findWhere(this.items, {id: id});
        obj.order = index;
        index++;
      });
    },

    setUpDragging() {
      if (window.drake) window.drake.destroy();
      window.drake = dragula({
        moves: function (el, container, handle) {
          return handle.className === 'handle-holder'
        },
        isContainer: function (el) {
          return el.classList.contains('will-sort');
        },
        direction: 'vertical'
      });

      window.drake.on('drop', (el, target, source, sibling) => {      
        this.saveOrder();
      });

    }

  },

  events: {
    'lister::selected'(data) {   
      this.addItem(data);
      this.modalVisible = false;
      return false;
    },

    'block::content::remove'(id) {
      for (var i = 0 ; i < this.items.length; i++) {
        if (this.items[i].id == id) {
          this.items.splice(i, 1);
          break;
        } 
      }
      return false;
    }
  },


  ready() {
    var resource = this.$resource('newsfeed{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();
  }
}
</script>