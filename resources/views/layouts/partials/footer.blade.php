<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-6 text-left">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <b>Version</b> 0.0.1
                    </li>

                </ul>
            </div>
            <div class="col-6 text-right">
                <p class="mb-0">
                    <strong>Copyright &copy; {{date("Y")}} <a href="http://vincar.cl">vincar.cl</a>.</strong> {{trans('comun.Todos_derechos')}}.
                </p>
            </div>
        </div>
    </div>
</footer>
<!--Modal cargando-->
<div class="modal" id="modalCargando" style="z-index: 1500; height: 450px">
    <div class="modal-dialog modal-lg modal-sm" style="width: 400px;">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h4 class="modal-title">Cargando...</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
              <img  src="{{asset('img/loading.gif')}}" class="img-fluid" />
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
