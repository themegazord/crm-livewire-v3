<div>
  <x-modal wire:model="modal" title="Visualização dos dados do usuário" subtitle="Você está visualizando os dados do usuário: {!! $usuario?->name !!}" separator class="backdrop-blur">

    <x-form>

      <x-input label="Nome completo" value="{!! $usuario?->name !!}" readonly />
      <x-input label="Email" value="{{ $usuario?->email }}" />
      <x-input label="Data de criação do usuário" value="{{ date('d/m/Y H:i:s', strtotime($usuario?->created_at)) }}" />
      <x-input label="Data da última atualização do usuário" value="{{ date('d/m/Y H:i:s', strtotime($usuario?->updated_at)) }}" />

      @if ($usuario?->trashed())
      <hr>

      <span class="text-red-500 font-bold">Dados de inativação do cadastro</span>

      <x-input label="Data de inativação do usuário" value="{{ date('d/m/Y H:i:s', strtotime($usuario?->deleted_at)) }}" />
      <x-input label="Nome do administrador responsável" value="{{ $usuario?->remover->name }}" />
      @endif

      @if(!$usuario?->trashed() && !is_null($usuario?->restorer_id))
      <hr>

      <span class="text-green-500 font-bold">Dados de reativação do cadastro</span>

      <x-input label="Data de reativação do usuário" value="{{ date('d/m/Y H:i:s', strtotime($usuario?->restored_at)) }}" />
      <x-input label="Nome do administrador responsável" value="{{ $usuario?->restorer->name }}" />
      @endif
    </x-form>


    <x-slot:actions>
      <x-button label="Cancelar" @click="$wire.modal = false" />
    </x-slot:actions>
  </x-modal>
</div>
