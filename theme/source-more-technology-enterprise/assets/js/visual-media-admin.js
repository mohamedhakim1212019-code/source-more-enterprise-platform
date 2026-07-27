(function($){
  'use strict';
  $(document).on('click','.smt-select-image',function(event){
    event.preventDefault();
    const card=$(this).closest('[data-visual-card]');
    const frame=wp.media({title:'Choose visual image',button:{text:'Use this image'},multiple:false});
    frame.on('select',function(){
      const attachment=frame.state().get('selection').first().toJSON();
      card.find('.smt-visual-url').val(attachment.url).trigger('change');
      card.find('.smt-visual-preview img').attr('src',attachment.url);
    });
    frame.open();
  });
  $(document).on('click','.smt-reset-image',function(event){
    event.preventDefault();
    const card=$(this).closest('[data-visual-card]');
    card.find('.smt-visual-url').val('');
    card.find('.smt-visual-preview img').attr('src',$(this).data('default'));
  });
  $(document).on('change','.smt-visual-url',function(){
    const value=$(this).val().trim();
    if(value)$(this).closest('[data-visual-card]').find('.smt-visual-preview img').attr('src',value);
  });
})(jQuery);
