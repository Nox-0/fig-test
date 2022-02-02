<template>
    <div>
        <div>
            <el-button type="primary" @click="getValue">Apply</el-button>
            <el-input-number label="Quantity" :min="1" v-model="quantity"></el-input-number>
            <template v-if="typeof(value) === 'number'">
                 Value: ${{value.toFixed(2)}}
            </template>
            <template v-else-if="typeof(value) === 'string'">
                {{value}}
            </template>
            <br>
        </div>
        <br>
        <div>
            <br>
            <el-button type="primary" @click="postTransaction">Create new transaction</el-button>
            <el-select v-model="transactionType" placeholder="Type">
                <el-option
                    v-for="item in transactionTypes"
                    :key="item"
                    :label="item"
                    :value="item">
                </el-option>
            </el-select>
            <el-input-number label="Quantity" v-model="transactionQuantity"></el-input-number>
            <el-input-number label="Unit Price" :step="0.1" :precision="2" :min="0" v-model="transactionUnitPrice"></el-input-number>
            <p>{{addTransactionString}}</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
    export default {
        data() {
            return {
                transactionType: '',
                transactionQuantity: 0,
                transactionUnitPrice: 0,
                quantity: 0,
                value: 0,
                addTransactionString: '',
                transactionTypes: [
                    'Application',
                    'Purchase'
                ]
            }
        },

        methods: {
            getValue() {
                this.value = axios.get('/api/getvalue', {
                    params: {
                        quantity_needed: this.quantity
                    }
                }).then(response => {
                    this.value = response.data;
                }).catch(error => {
                    console.log(error);
                    this.value = 'Quantity requested is not available';
                });
            },

            postTransaction() {
                if (this.validateTransaction()) {
                    axios.post('api/create', {
                        type: this.transactionType,
                        quantity: this.transactionQuantity,
                        unit_price: this.transactionUnitPrice
                    }).then(response => {
                        console.log(response);
                        this.addTransactionString = 'Successfully created transaction';
                    }).catch(error => {
                        console.log(error);
                        this.addTransactionString = 'Oops, something went wrong. Check the console';
                    })
                }
            },

            validateTransaction() {
                if (this.transactionType === 'Application') {
                    if (this.transactionQuantity < 0) {
                        return true;
                    }
                    this.addTransactionString = 'Transaction quantity must be negative for an Application transaction';
                    return false;
                }
                if (this.transactionType === 'Purchase') {
                    if (this.transactionQuantity > 0) {
                        return true
                    }
                    this.addTransactionString = 'Transaction quantity be positive for a Purchase transaction';
                    return false
                }
            }
        }
    }
</script>
