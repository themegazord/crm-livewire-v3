<div class="flex flex-col">
  <x-header title="Listagem de usuários" separator />
  <x-input label="Insira o id, nome ou email de algum usuário que deseja consultar" wire:model.live="consulta" placeholder="Id, nome, email..." clearable />
  <x-table
    :headers="$this->headers()"
    :rows="$this->usuarios()"
    with-pagination
    striped
    class="mt-[3rem]"
    per-page="perPage"
    :sort-by="$sortBy"
    :per-page-values="[3, 5, 10]">

    @scope('cell_permissoes', $usuario)
    @foreach ($usuario->permissoes as $permissao)
    <x-badge :value="$permissao->permissao" class="badge-primary" />
    @endforeach
    @endscope

    @scope('actions', $usuario)
    <x-button icon="o-trash" wire:click="delete({{ $usuario->id }})" spinner class="btn-sm"></x-button>
    @endscope
  </x-table>
</div>
