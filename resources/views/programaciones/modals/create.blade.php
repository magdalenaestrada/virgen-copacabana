  <div class="modal fade" id="modalProgramacion" tabindex="-1" aria-labelledby="modalProgramacionLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content border-0 shadow-lg rounded-4">
              <div class="modal-header bg-light border-0">
                  <h5 class="modal-title fw-semibold" id="modalProgramacionLabel">Añadir Programación</h5>
              </div>

              <div class="modal-body bg-light p-4">
                  <form id="formProgramacion">
                      @csrf
                      <input type="hidden" id="programacion_id" value="">
                      <div class="row g-2">
                          <div class="col-md-4">
                              <label class="form-label fw-semibold">Lote</label>

                              <div class="combo">
                                  <input type="text" id="lote_id_input" class="form-control"
                                      placeholder="Escriba o seleccione...">

                                  <select id="lote_id" class="form-control" size="5" style="display:none;">
                                      @foreach ($lotes as $lote)
                                          <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                                      @endforeach
                                  </select>

                                  <input type="hidden" id="lote_id_real">
                              </div>
                          </div>
                          <div class="col-md-4">
                              <label class="form-label fw-semibold">Fecha inicio</label>
                              <input type="datetime-local" class="form-control" id="fecha_inicio"
                                  value="{{ $hoy }}" required>
                          </div>
                          <div class="col-md-4">
                              <label class="form-label fw-semibold">Fecha fin</label>
                              <input type="datetime-local" class="form-control" id="fecha_fin">
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-4">
                              <label class="form-label fw-semibold">Circuito</label>
                              <select id="circuito" class="form-control">
                                  <option value="">Seleccione</option>
                                  <option value="A">A</option>
                                  <option value="B">B</option>
                              </select>
                          </div>
                          <div class="col-md-4 offset-md-4 text-end">
                              <label class="form-label fw-semibold">Peso Total (kg)</label>
                              <input type="number" step="0.01" id="peso_total" class="form-control" readonly>
                          </div>
                      </div>

                      <div class="table-responsive bg-white rounded-3 shadow-sm p-3">
                          <table class="table table-sm align-middle text-center" id="tablaPesos">
                              <thead class="table-light">
                                  <tr>
                                      <th>Seleccionar</th>
                                      <th>N° Salida</th>
                                      <th>Fecha</th>
                                      <th>Peso Bruto</th>
                                      <th>Tara</th>
                                      <th>Neto</th>
                                      <th>Hora Inicio</th>
                                      <th>Hora Fin</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td colspan="8" class="text-muted">Seleccione un lote para ver los pesos...
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </form>
                  <div id="otra_balanza">
                      <form id="formPesoManual">
                          <div class="row g-3">
                              <div class="col-md-3">
                                  <label class="form-label">Conductor</label>
                                  <input type="text" class="form-control" name="conductor">
                              </div>
                              <div class="col-md-1">
                                  <label class="form-label">Placa</label>
                                  <input type="text" class="form-control" name="placa">
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Origen</label>
                                  <input type="text" class="form-control" name="origen">
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Destino</label>
                                  <input type="text" class="form-control" name="destino">
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Guia</label>
                                  <input type="text" class="form-control" name="guia">
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">GuiaT</label>
                                  <input type="text" class="form-control" name="guiat">
                              </div>
                          </div>
                          <div class="row g-3">
                              <div class="col-md-3">
                                  <label class="form-label">Balanza</label>
                                  <input type="text" class="form-control" name="balanza">
                              </div>
                              <div class="col-md-3">
                                  <label class="form-label">Producto</label>
                                  <input type="text" class="form-control" name="producto">
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Bruto (kg)</label>
                                  <input type="number" class="form-control" name="bruto" required>
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Tara (kg)</label>
                                  <input type="number" class="form-control" name="tara" required>
                              </div>
                              <div class="col-md-2">
                                  <label class="form-label">Neto (kg)</label>
                                  <input type="number" class="form-control" name="neto" readonly>
                              </div>
                          </div>
                          <div class="row g-3">
                              <div class="col-md-12">
                                  <label class="form-label">Observación</label>
                                  <input class="form-control" name="observacion">
                              </div>
                          </div>
                      </form>
                      <div class="mt-3 d-flex justify-content-end g-1">
                          <button type="button" class="btn btn-success btn-sm" id="btnAgregarPesoManual">
                              <i class="bi bi-plus-circle me-1"></i> Agregar Peso de Otra Balanza
                          </button>
                      </div>
                      <br>
                      <div class="row">
                          <div class="table-responsive bg-white rounded-3 shadow-sm p-3">
                              <table class="table table-sm align-middle text-center" id="tablaOtrasBalanzas">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>Fecha I.</th>
                                          <th>Fecha S.</th>
                                          <th>Placa</th>
                                          <th>Conductor</th>
                                          <th>Bruto</th>
                                          <th>Tara</th>
                                          <th>Neto</th>
                                          <th>Balanza</th>
                                          <th>Producto</th>
                                          <th>Estado</th>
                                          <th>Acciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td colspan="9" class="text-muted">No hay pesos de otras balanzas
                                              registrados
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="modal-footer bg-light border-0">
                  <button type="button" class="btn btn-outline-secondary px-4" id="btnCancelar">Cancelar</button>
                  <button type="button" class="btn btn-primary px-4" id="btnGuardar">Guardar</button>
              </div>
          </div>
      </div>
  </div>
