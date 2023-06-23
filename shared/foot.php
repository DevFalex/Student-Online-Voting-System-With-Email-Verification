<script src="../jquery/jquery.min.js"></script>
<script src="../bootstrap/dist/js/bootstrap.min.js"></script>  
<script src="../jquery/popper.js"></script>
<script src="../jquery/canvasjs.min.js"></script>
<script src="../jquery/jquery.dataTables.min.js"></script>
<script src="../src/js/select2.min.js"></script>  
<script>
try {
 const containerFluid = document.getElementsByClassName('container-fluid');
 containerFluid[0].classList.add('position-absolute');
 containerFluid[0].classList.add('shows');
} catch(e) {
    
}
 function stripeTable() {
     if(document.getElementsByClassName('table').length) {
         const len = document.getElementsByClassName('table').length;
         const element = document.getElementsByClassName('table');
         for(const el  of element) {
             el.classList.add('table-striped');
         }
     }
 }
 stripeTable();
</script>