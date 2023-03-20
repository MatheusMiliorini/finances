<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue';

const props = defineProps({
  errors: Object,
  accounts: Array
});

const total = computed(() => {
  let total = 0;
  props.accounts.forEach(account => {
    total += account.currentBalance;
  })
  return total;
});

const accountForm = ref({
  _id: null,
  name: '',
  balance: 0
});

const saveAccount = () => {
  router.post('/account', {
    name: accountForm.value.name,
    balance: accountForm.value.balance * 100,
  }, {
    onSuccess: () => {
      accountForm.value = {
        _id: null,
        name: '',
        balance: 0
      }
      document.getElementById('closeModal').click()
    }
  });
}
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
            <div class="row">
              <div v-for="account in accounts" :key=account._id class="col-4 mb-1">
                <div class="badge text-bg-primary" style="width: 100%;">
                  <p class="my-0">{{ account.name }}</p>
                  <p class="my-0 mt-1">R$ {{ (account.currentBalance / 100).toFixed(2) }}</p>
                </div>
              </div>
              <div class="col-4">
                <button class="btn btn-success" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#accountModal">
                  Adicionar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="accountModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ accountForm._id ? 'Editar' : 'Criar' }} Conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="accountForm" @submit.prevent="saveAccount" novalidate>
            <div class="row">
              <div class="col-6">
                <label for="name" class="form-label">Nome</label>
                <input type="text" :class="{ 'is-invalid': errors.name }" class="form-control" id="name"
                  placeholder="Carteira" v-model="accountForm.name">
                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
              </div>
              <div class="col-6">
                <label for="balance" class="form-label">Saldo</label>
                <input type="number" :class="{ 'is-invalid': errors.balance }" class="form-control" id="balance"
                  placeholder="15.00" v-model="accountForm.balance">
                <div class="invalid-feedback" v-if="errors.balance">{{ errors.balance }}</div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Cancelar</button>
          <button type="submit" form="accountForm" class="btn btn-primary">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</template>