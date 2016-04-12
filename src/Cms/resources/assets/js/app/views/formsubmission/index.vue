<template>

  
<div class="content">
  <div class="card card-block">

    <div class="row">
      <div class="col-sm-3">
        <hr />
        <h3>{{ items.total }} Submissions</h3>
      </div>  
    </div>
  </div>

    <div class="loading" v-if="!items"><div class="spinner"></div></div>

    <div class="card card-default">
      <div class="card-header">
        Available Columns
      </div>
      <div class="card-block">
        <span v-for="column in columns">
            <label>
              <input type="checkbox" name="visible" v-bind:value="column" v-model="visibleColumns" @click="updateColumnDisplay"> {{ columnNames[column] }} &nbsp;
            </label>      
        </span>
      </div>
    </div>

    <table class="table"> 
    <tr v-for="item in items.data">
      <td>       
          <div class="row">
            <div class="col-xs-1">
               {{ item.id }}
            </div>
            <span v-for="column in item.form_data">
              <div class="col-xs-1" v-if="column.visible">
                {{ column['answer_' + column.answer_type] }}
              </div>
            </span>
          </div>
      </td>
    </tr>

    </table>  


        <nav v-if="items.last_page > 1">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" @click="prevPage">
                <span>&laquo;</span>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link">
                <span>{{ items.current_page }} of {{ items.last_page }}</span>
              </a>
            <li class="page-item">
              <a class="page-link" @click="nextPage">
                <span>&raquo;</span>
              </a>
            </li>
          </ul>
        </nav> 

    </div>


</template>

<script>
export default {
  data() {
    return {
      questions: [],
      resource: null,
      id: null,
      items: {
        data: [],
        last_page: 1,
        current_page: 1,
      },
      columns: [],
      columnNames: [],
      visibleColumns: []
    } 
  },

  methods: {

    updateColumnDisplay() {

      setTimeout(() => {
        for (var i = 0; i< this.items.data.length; i++) {
          var row = this.items.data[i];

          for (var c = 0; c < row.form_data.length; c++) {
            var col = row.form_data[c];

            if (_.indexOf(this.visibleColumns, col.question_id) >= 0) {
              col.visible = true;
            } else {
              col.visible = false;
            }

          }

        }
      }, 0);

    },


    sortDataColumns: function(data) {

      var sorted = [];

      for (var i = 0; i < data.length; i++) {
        var sortable = [];

        for (var item in data[i].form_data) {
          sortable.push(data[i].form_data[item])
        }

        sortable.sort(function(a, b) {
          return a.question.order >= b.question.order;
        });

        var obj = data[i];
        obj.form_data = sortable;
        sorted.push(obj);
      }


      return sorted;
     
    },

   nextPage() {
      if (this.items.current_page == this.items.last_page) return;
      this.getItems( this.items.current_page + 1);
    },

    prevPage() {
      if (this.items.current_page == 1) return;
      this.getItems( this.items.current_page - 1);
    },

    confirmRemoval(id) {
      this.$broadcast('confirm::ask', id);
    },

    removeItem(id) {
      this.resource.delete({id}).then((response) => {
        this.clearItemFromList(id);
      });
    },

    clearItemFromList(id) {
      for (var i = 0; i < this.items.data.length; i++) {
        if (this.items.data[i].id == id) {
          this.items.data.splice(i, 1);
          break;
        }
      }
    },

    getItems(pageNumber) {

      var options = {
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get({id: this.id}, options).then((response) => {    

          for (var i = 0; i < response.data.data.length; i++) {

            var row = response.data.data[i];

            for (var c = 0; c < row.form_data.length; c++) {

              var col = row.form_data[c];
              response.data.data[i].form_data[c].visible = false;

              if (_.indexOf(this.columns, col.question.id) < 0) {

                this.columns.push(col.question.id);
                this.columnNames[col.question.id] = col.question.question;

              }

            }
          }

          this.$set('items', response.data);
      }); 

    },

  },

  ready() {
    var resource = this.$resource('form{/id}/submission{/subId}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItems(1);
  }  
}
</script>