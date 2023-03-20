<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
  accounts: Array
});

const total = ref(0);
props.accounts.forEach(account => {
  total.value += account.currentBalance;
});
</script>

<template>
  <Head title="Finances Dashboard" />

  <div class="container mt-2">
    <div class="row">
      <div class="col-md-3 col-12">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">Saldo Total</h5>
            <p>R$ {{ (total / 100).toFixed(2) }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center">Contas</h5>
            <div class="d-flex">
              <span v-for="account in accounts" :key=account._id class="badge text-bg-primary mx-2">
                {{ account.name }}
                <br>
                R$ {{ (account.currentBalance / 100).toFixed(2) }}
              </span>
              <button class="btn btn-sm btn-success">
                Adicionar Conta
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>