<template>
    <div class="container d-flex justify-content-center mt-3">
        <div class="card col-6">
            <div class="card-header">
                PHP Challenge 20201117
            </div>
            <div class="card-body">
                <div>
                    <h5 class="card-title mb-3" v-if="token !== ''">Api Key: {{ token }}</h5>
                    <h5 class="card-title mb-3" v-else>Api Key: {{ 'No token configured'}}</h5>
                </div>
                <input v-model="tokenInput" v-on:input="setToken()" class="form-control my-2" type="text" placeholder="Put the generated API Key here">
                <div class="btn btn-success" v-on:click="generateApiToken()">Generate API Key</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                token: '',
                tokenInput: ''
            }
        },
        methods: {
            generateApiToken() {
                axios.get('/create-token', {
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                }).then(res => {
                    this.token = res.data.token

                }).catch(err => {
                    console.log(err)
                })
            },
            setToken() {
                document.head.querySelector('meta[name="api_token"]').setAttribute('content', this.tokenInput)
            }
        }
    }
</script>
