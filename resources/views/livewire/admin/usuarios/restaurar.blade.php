<div>
  <x-modal wire:model="modal" title="Confirmação de restauração do cadastro" subtitle="Você está reativando o cadastro do(a) {!! $usuario?->name !!}" separator class="backdrop-blur">

    @error('confirmacao')
    <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
      {{ $message }}
    </x-alert>
    @enderror

    <div class="mb-5">
      <x-input label="Escreva `{!! $usuario?->name !!}` para confirmar a reativação do usuário." wire:model="confirmacao_confirmation" />
    </div>


    <x-slot:actions>
      <x-button label="Cancelar" @click="$wire.modal = false" />
      <x-button label="Reativar" class="btn-primary" wire:click="restore" />
    </x-slot:actions>
  </x-modal>
</div>
