<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {createForm} from "@inertiajs/vue3";
import {store} from "@/actions/App/Http/Controllers/Web/AuthenticatedSessionController";
  interface LoginForm {
      email: string,
      password: string,
  }

const LoginForm = createForm<LoginForm>()
</script>

<template>
    <Head title="Login" />
    <div class="flex flex-col py-10 items-center min-h-screen">
        <h1 class="mb-6 font-semibold">Demo Booking System</h1>
        <LoginForm
            v-bind="store.form()"
            #default="{ errors, processing }"
            class="border border-gray-200 bg-white p-6 flex flex-col max-w-3xl"
        >
            <div class="grid grid-cols-2 p-4">
                <label>Email</label>
                <input type="email" name="email" class="border" />
            </div>
            <p v-if="errors.email" class="text-sm text-red-500">{{ errors.email }}</p>
            <div class="grid grid-cols-2 p-4">
                <label>Password</label>
                <input type="password" name="password" class="border" />
            </div>

            <button :disabled="processing" type="submit" class="border rounded-2xl bg-blue-300 disabled:opacity-50">{{ processing ? 'Signing in...' : 'Login'}}</button>
        </LoginForm>
    </div>
</template>
