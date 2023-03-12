<button class='no-print' onclick="window.print()">{{__("LNK_PRINT")}}</button>
{!! $configValues['PACKING_SLIP_HTML'] !!}
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>