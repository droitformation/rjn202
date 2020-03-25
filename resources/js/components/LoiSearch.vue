<template>
    <div class="container">

        <div class="row">
            <div class="col-md-4 col-xs-5 col-search-md">
                <Select2 v-model="loi_id" :options="instances" @change="selectedLoi($event)" @select="selectedLoi($event)" :settings="{ placeholder: 'Choisir la loi' }" />
            </div>

            <div class="form-group col-md-2 col-small col-search">
                <Select2 v-model="article" :options="dispositions" @change="selectedArticle($event)" @select="selectedArticle($event)" :settings="{ placeholder: 'Choisir l\'article' }" />
            </div>

            <div class="form-group col-md-2 col-small col-search">
                <Select2 v-if="notes.length" v-model="selected" :options="notes" @change="selectedSubdivision($event)" @select="selectedSubdivision($event)" :settings="{ placeholder: 'Choisir la subdivision' }"/>
            </div>

            <input type="hidden" :value="loi_id" name="loi">
            <input type="hidden" :value="article" name="article">

            <div v-if="model">
                <input type="hidden" v-if="model.alinea" :value="model.alinea" name="alinea">
                <input type="hidden" v-if="model.chiffre" :value="model.chiffre" name="chiffre">
                <input type="hidden" v-if="model.lettre" :value="model.lettre" name="lettre">
            </div>

            <button type="submit" class="btn btn-danger">Voir</button>
        </div>
    </div>
</template>
<script>
    import Select2 from 'v-select2-component';

    export default {
        props: ['lois','articles'],
        data(){
            return{
                url: location.protocol + "//" + location.host+"/",
                instances: this.lois,
                dispositions:[],
                selected:null,
                loi_id:null,
                article:null,
                notes:[],
                model:[],
            }
        },
        components: {Select2},
        mounted() {
            console.log('Component mounted.');
        },
        computed: {},
        methods: {
            droit(id){
                if(id == 1){
                    return 'Droit fédéral';
                }
                if(id == 2){
                    return 'Droit cantonal';
                }
                if(id == 3){
                    return 'Droit international';
                }
            },
            selectedLoi(){
                let self = this;

                axios.post(this.url +'ajax/articles', { loi_id:this.loi_id }).then(function (response) {
                    self.dispositions = response.data;
                }).catch(function (error) { console.log(error);});
            },
            selectedArticle(){
                let self = this;

                axios.post(this.url +'ajax/notes', { loi:this.loi_id, article: this.article }).then(function (response) {
                    self.notes = response.data;
                }).catch(function (error) { console.log(error);});
            },
            selectedSubdivision(event){
               this.model = event.other;
            }
        }
    }
</script>
<style>
    .select2-container .select2-selection--single {
        height: 32px !important;
        border: 1px solid #CCCCCC;
    }
</style>

