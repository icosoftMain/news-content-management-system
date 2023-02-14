<div
    style="
        width: {:val.W}%;
        height: {:val.H}px;
        margin: 0 auto;
        border-radius: 50%;
        background-image: url(@statics({:str.imageName}));
        background-size: cover;
    "
></div>
@if !is_empty({:str.imageTitle}):
    <h4 style="width: 100%; text-align: center;">{:val.imageTitle}</h4>
@endif