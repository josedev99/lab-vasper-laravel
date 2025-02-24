<div class="modal fade" id="modal_new_examen" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg modal_shadow">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">REGISTRAR NUEVO EXAMEN</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_examenCat">
                    <div class="card p-1 m-0">
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-7">
                                    <div class="content-input mb-2">
                                        <input name="examen" type="text" class="custom-input material" value=""
                                            placeholder=" " placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="examen">Examen</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-5">
                                    <div class="input-group mb-2">
                                        <label for="categoria" class="input-group-title1">Categorias </label>
                                        <select name="categoria" id="categoria_id" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar categoria examen">
                                            <option value="">Selecccionar</option>
                                            @foreach($cat_examenes as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                        <label class="input-group-label1 bg-info btnAdd btn-add-catego"><i
                                                class="bi bi-plus-lg" style="font-size: 16px"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i>
                                Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>