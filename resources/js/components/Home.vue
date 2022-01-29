<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>

                    <template v-for="inv in inventory">
                        {{inv.date}}
                        {{inv.type}}
                        {{inv.quantity}}
                        {{inv.unitPrice}}<br/>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                inventory: [
                    {
                        date: '05/06/2020',
                        type: 'Purchase',
                        quantity: 10,
                        unitPrice: 5
                    },
                    {
                        date: '07/06/2020',
                        type: 'Purchase',
                        quantity: 30,
                        unitPrice: 4.5
                    },
                    {
                        date: '08/06/2020',
                        type: 'Application',
                        quantity: -20,
                    },
                ]
            }
        }
    }
</script>
