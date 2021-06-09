<template>
<div class="LoiSearchContainer">
  <input type="hidden" :name="fieldname" :value="currentInputValue">
  <vue-suggestion :items="items" 
                  v-model="item"   
                  :setLabel="setLabel"                 
                  :itemTemplate="itemTemplate"
                  :minLen="minLen"
                  :placeholder="placeholder"
                  @changed="inputChange" 
                  @selected="itemSelected"
                  @enter="manageKeyEnter"
                  @mousedown="downlb"
                  @mouseup="uplb"
                  @click="clicklb">
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

const BaseSuggestion = Vue.options.components["vue-suggestion"];
const CustomSuggestion = BaseSuggestion.extend({
  methods:{
	  blur: function blur() {
	      var _this = this;

	      this.$emit('blur', this.searchText); // set timeout for the click event to work

	      setTimeout(function () {
	        _this.showList = true; //override base component !!!
	      }, 200);
	    },
  }
});

Vue.component("vue-suggestion", CustomSuggestion);

export default {
  props: ['fieldname','annees','pages','years_pages','custom_form_id'],
  data () {  
    return {
      item: {},
      items: [],
      itemTemplate,
      placeholder: "Numéro d’arrêt ou mots-clés",
      minLen:  3,
      initYears : this.annees,
      initPages : this.pages,
      initYearsPages : this.years_pages,
      currentInputValue : "",
      hasChanged : false,
      currentFormId : this.custom_form_id
    }
  },  
  methods: {
	downlb () {
		alert("down");
	},
	uplb () {
		alert("up");
	},
	clicklb () {
		alert("click");
	},
  	manageKeyEnter () {
  		if(this.hasChanged) {
  			this.hasChanged = false;
  		} else {
  			this.hasChanged = false;
  			document.getElementById(this.currentFormId.id).submit();
			return false;
  		}
  	},
    itemSelected (item) {
      this.item = item;
      this.items = this.initYearsPages;
      this.inputChange(this.item.name+" ");
      this.showList = true;
      this.searchText = this.item.name+" ";
    },
    setLabel (item) {
      return item.name;
    },
    inputChange (text) {
      this.currentInputValue = text;
      if( (text.length > 2) && ( (text.toLowerCase().includes("rjn")) || (!isNaN(text.replace(" ","")) && (parseInt(text) > 2000) ) ) ) {
                
        if( (!isNaN(text.replace(" ",""))) && (!text.toLowerCase().includes("rjn")) && (text.length < 9) && (parseInt(text) > 2000)) {
        	text = "rjn " + text;
        }
      
        this.hasChanged = true;	
      	if(text.length < 8) {		
      		this.items = this.initYearsPages;
      	} else {
      		this.items = this.initPages.filter(item => item.name.toLowerCase().includes(text.toLowerCase()));	
      	}
      } else {
      	this.items = [];
      	this.hasChanged = false;
      }      
    },
  },
  
};
</script>

