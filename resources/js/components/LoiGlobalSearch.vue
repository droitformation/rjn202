<template>
<div>
  <input type="hidden" :name="fieldname" :value="currentInputValue">
  <vue-suggestion :items="items" 
                  v-model="item"   
                  :setLabel="setLabel"                 
                  :itemTemplate="itemTemplate"
                  :minLen="minLen"
                  :placeholder="placeholder"
                  @changed="inputChange" 
                  @selected="itemSelected">
  </vue-suggestion>
  </div>  
</template>
<!-- some-sample-css-as-example-for-your-dropdown-autocomplete  -->
<style scope>
.vue-suggestion .vs__list {
    width: 100%;
    text-align: left;
    border: none;
    border-top: none;
    max-height: 400px;
    overflow-y: auto;
    border-bottom: 1px solid #023d7b;
    position: relative;
    z-index:999999999999999;
}
.vue-suggestion .vs__list .vs__list-item {
    background-color: #fff;
    padding: 10px;
    border-left: 1px solid #023d7b;
    border-right: 1px solid #023d7b;
}
.vue-suggestion .vs__list .vs__list-item:last-child {
    border-bottom: none;
}
.vue-suggestion .vs__list .vs__list-item:hover {
    background-color: #eee !important;
}
.vue-suggestion .vs__list,
.vue-suggestion .vs__loading {
    position: absolute;
}
.vue-suggestion .vs__list .vs__list-item {
    cursor: pointer;
}
.vue-suggestion .vs__list .vs__list-item.vs__item-active {
    background-color: #f3f6fa;
}

.vue-suggestion .vs__selected {
	background-color:transparent !important;
	border:none !important;
	margin:0 !important;
	padding:0 !important;
}

.vue-suggestion .vs__input {
	border: 1px solid rgba(60,60,60,.26);
	padding: 6px;
	width: 100%;
	display: inline-block;
	vertical-align: middle;
	font-size: 14px !important;
	color: #555 !important;
}

</style>
<script>
import VueSuggestion from 'vue-suggestion';
import itemTemplate from './LoiGlobalSearch-item-template.vue';

export default {
  props: ['fieldname','annees','pages'],
  data () {  
    return {
      item: {},
      items: [],
      itemTemplate,
      placeholder: "Mots clés",
      minLen:  3,
      initYears : this.annees,
      initPages : this.pages,
      currentInputValue : "",
    }
  },  
  methods: {
    itemSelected (item) {
      this.item = item;
      this.items = this.initPages;
      this.inputChange(this.item.name+" ");
    },
    setLabel (item) {
      return item.name;
    },
    inputChange (text) {
      this.currentInputValue = text; 
      // your search method
      if(text.toLowerCase().includes("rjn")) {
	      if(text.length < 8) {		  
		      	this.items = this.initYears.filter(item => item.name.toLowerCase().includes(text.toLowerCase()));		      			 
		  } else {		  	  
		      	this.items = this.initPages.filter(item => item.name.toLowerCase().includes(text.toLowerCase()));		      	
		  }
	  }
    },
  },
  
};
</script>

