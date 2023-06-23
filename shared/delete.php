<div class="modal fade" id="deleteServiceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> 
            <div class="modal-header">
                <h4 class="modal-title">Delete</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" name="add" onclick="deleteService.delete()"><i class="fa fa-trash"></i> Delete<div>
                <button type="button"  data-dismiss="modal" class="btn btn-primary" name="add"><i class="fa fa-remove"></i> Cancel<div>
            </div>
        </div>
    </div>
</div>
<script>
    const deleteService = {
        id: null,
        url: null,
        method: null,
        isset:null,
        request: (request)=>{
            this.id=request.id;
            this.url=request.url;
            this.method=request.method;
            this.isset=request.isset;
            this.complete=request.complete;
            $('#deleteServiceModal').modal('show');
        },
        complete:(e)=>{
            alert('asd');
        },
        delete:() => {
            const formData = new FormData();
            formData.append(this.isset,this.id);
                $.ajax(
                    {   
                        url:this.url,
                        method:this.method,
                        data:formData,
                        processData:false,
                        contentType:false,
                        success:(e)=>{ 
                            this.complete(e); 
                        }
                    }
                );
            },
            close:()=>{
                $('#deleteServiceModal').modal('hide');
            }
    }
</script>