<template>
  <div class="container">
      <div class="content">
        <div class="card">
          <div class="card-block">
            <h1>Dashboard</h1>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <h5>Last Edited Pages</h5>
            <ul>
              <li v-for="page in lastEdits">
                <div><strong><a v-link="{name:'page-edit', params: {id: page.id}}">{{ page.title }}</a></strong></div>
                <div class="text-muted">{{ prettyDate(page.updated_at) }}</div>
              </li>
            </ul>
          </div>    
          <div class="col-sm-6">
              <h5>Scheduled Pages</h5>
              <ul>
                <li v-for="page in scheduledPages">
                  <div><strong><a v-link="{name:'page-edit', params: {id: page.id}}">{{ page.title }}</a></strong></div>
                  <div class="text-muted">{{ prettyDate(page.scheduled_publish) }}</div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  data() {
    return {
      resource: null,
      lastEdits: [],
      scheduledPages: []
    }
  },


  methods: {
    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('lastEdits', response.data.last_edited);
        this.$set('scheduledPages', response.data.scheduled_publishing);
      }); 
    },

    prettyDate(dateString) {
      return moment(dateString).format('ddd MMM Do h:mm A');
    }
  },

  ready() {
    var resource = this.$resource('dashboard');
    this.$set('resource', resource);
    this.getItem();
  }
};
</script>