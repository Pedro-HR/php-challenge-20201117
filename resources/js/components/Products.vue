<template>
    <div class="container w-75 mt-3">
        <div class="my-3">
            <div class="row justify-content-center">
                <input class="form-control w-50 me-2" type="file" id="formFile" ref="file" v-on:change="onChangeFileUpload()">
                <div class="btn btn-primary col-1" v-on:click="sendFile()">Send</div>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="col">Title</th>
                    <th class="col">Type</th>
                    <th class="col">Rating</th>
                    <th class="col">Price</th>
                    <th class="col">Created</th>
                    <th class="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="products == ''" class="text-center">
                    <td colspan="6">Configure the API and enter the products.json to view them in the table</td>
                </tr>

                <tr v-else v-for="(product) in products">
                    <td class="col">{{ product.title }}</td>
                    <td class="col">{{ product.type }}</td>
                    <td class="col">{{ product.rating }}</td>
                    <td class="col"> $ {{ new Intl.NumberFormat().format(product.price) }}</td>
                    <td class="col">{{ product.date }}</td>
                    <td class="col">
                        <span role="button" class="me-2"
                              data-bs-toggle="modal" data-bs-target="#edit_product"
                              v-on:click="getProduct(product.code)">Edit</span>
                        <span role="button" data-bs-toggle="modal" v-on:click="setDeletedCode(product.code)"
                              data-bs-target="#delete_product">Delete</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="edit_product" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="title" class="col-2 col-form-label">Title: </label>
                            <div class="col-10">
                                <input type="text" class="form-control" id="title"
                                       v-model="product.title">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="type" class="col-2 col-form-label">Type: </label>
                            <div id="type" class="col-10">
                                <select class="form-select" v-model="product.type">
                                    <option value="dairy">dairy</option>
                                    <option value="fruit">fruit</option>
                                    <option value="vegetable">vegetable</option>
                                    <option value="bakery">bakery</option>
                                    <option value="vegan">vegan</option>
                                    <option value="meat">meat</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="price" class="col-2 col-form-label">Price: </label>
                            <div class="col-10">
                                <input type="text" class="form-control" id="price"
                                       v-model.number="product.price" @keypress="isNumber($event)">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" v-on:click="updateProduct(product.code)">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete_product" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you would like to delete the product?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                v-on:click="destroyProduct(product.code)">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: '',
                deletedCode: '',
                products: [],
                product: [{
                    title: '',
                    type: '',
                    price: '',
                }],
            }
        },
        methods: {
            onChangeFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            sendFile() {
                let formData = new FormData();
                formData.append('products-file', this.file);
                console.log(document.head.querySelector('meta[name="api_token"]').getAttribute('content'))

                axios.post('/products', formData, {
                    headers: {
                        'X-CSRF-TOKEN' : document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'api_token' : document.head.querySelector('meta[name="api_token"]').getAttribute('content'),
                    }
                }).then(res => {
                    alert(res.data.messege)
                    this.getProducts()
                }).catch(err => {
                    console.log(err)
                })
            },
            getProducts() {
                this.products = 'temp'

                axios.get('/products', {
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'api_token' : document.head.querySelector('meta[name="api_token"]').getAttribute('content'),
                    }
                }).then(res => {
                    this.products = res.data.data
                }).catch(err => {
                    console.log(err)
                })
            },
            setDeletedCode(productCode) {
                this.deletedCode = productCode
            },
            destroyProduct() {
                axios.delete(`/products/${this.deletedCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'api_token' : document.head.querySelector('meta[name="api_token"]').getAttribute('content'),
                    }
                }).then(res => {
                    this.getProducts()
                }).catch(err => {
                    console.log(err)
                })
            },
            getProduct(productCode) {
                this.product = []

                axios.get(`/products/${productCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'api_token' : document.head.querySelector('meta[name="api_token"]').getAttribute('content'),
                    }
                }).then(res => {
                    this.product = res.data.data
                    this.title = res.data.data.title
                }).catch(err => {
                    console.log(err)
                })
            },
            updateProduct(productCode) {
                axios.put(`/products/${productCode}`, this.product,{
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'api_token' : document.head.querySelector('meta[name="api_token"]').getAttribute('content'),
                    }
                }).then(res => {
                    alert(res.data.messege)
                    this.getProducts()
                }).catch(err => {
                    console.log(err)
                })
            },
            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            }
        }
    }
</script>
