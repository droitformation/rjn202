<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-5 col-search-md">
                <select data-placeholder="Loi" class="chosen-vue" name="loi">
                    <optgroup v-for="(instance,index) in instances" :label="droit(index)">
                        <option v-for="loi in instance" :value="loi.id">{{ loi.sigle }}</option>
                    </optgroup>
                </select>

            </div>
            <div class="form-group col-md-2 col-small col-search">
                <select v-if="dispositions" data-placeholder="Article" class="chosen-vue2" name="article">
                    <option v-for="(disposition,art) in dispositions" :value="art">Art. {{ art }}</option>
                </select>
                {{ notes }}

            </div>
            <div class="form-group col-md-2 col-small col-search">
                <input type="text" class="form-control search_input" name="alinea" id="select_alinea" placeholder="Alinéa">
            </div>
            <div class="form-group col-md-2 col-small col-search">
                <input type="text" class="form-control search_input" name="lettre" id="select_lettre" placeholder="Lettre">
            </div>
            <div class="form-group col-md-2 col-small col-search">
                <input type="text" class="form-control search_input" name="chiffre" id="select_chiffre" placeholder="Chiffre">
            </div>
            <button type="submit" class="btn btn-danger">OK</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['lois','articles'],
        data(){
            return{
                url: location.protocol + "//" + location.host+"/",
                instances: this.lois,
                dispositions:null,
                loi_id:null,
                article:null,
                notes:null
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.initialize();
        },
        computed: {

        },
        methods: {
            initialize: function () {

                let self = this;

                this.$nextTick(function(){
                    $(".chosen-vue").prepend("<option value='' selected='selected'>Choisir la Loi</option>");
                    $('.chosen-vue').chosen({ width:"95%"});
                    $(".chosen-vue").on('change', function(event, params) {
                        self.dispositions = null;
                        self.selectedLoi($(this).val());
                    });
                });
            },
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
            selectedLoi(loi){
                let articles = this.articles[loi];
                this.dispositions = articles;
                this.loi_id = loi;

                let self = this;

                this.$nextTick(function(){

                    $(".chosen-vue2").prepend("<option value='' selected='selected'>Choisir l'article</option>");
                    $('.chosen-vue2').chosen({ width:"95%"});
                    $(".chosen-vue2").on('change', function(event, params) {
                        self.selectedArticle($(this).val());
                    });

                });
                console.log(articles);
            },
            selectedArticle(cote){
                let self = this;
                axios.post(this.url +'ajax/notes', { loi:this.loi_id, article: cote }).then(function (response) {
                    console.log(response);
                    self.notes = response.data;
                }).catch(function (error) { console.log(error);});
            }
        }
    }
</script>
