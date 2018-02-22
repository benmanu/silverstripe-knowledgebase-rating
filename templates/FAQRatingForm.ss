<h1 class="faq-page__rating-title">Did this article answer your question?</h1>
<form $AttributesHTML>
  <% if $Message %>
    <p id="{$FormName}_error" class="faq-rating-form__message message $MessageType">$Message</p>
  <% else %>
    <p id="{$FormName}_error" class="faq-rating-form__message message $MessageType" style="display: none"></p>
  <% end_if %>

  <div class="faq-rating-form__input-group input-group">
    <div id="faq-rating-form-rate-success-message">
      <% with $Fields.dataFieldByName('Rating') %>
        $Field
      <% end_with %>
    </div>
    <div id="faq-rating-form-submit-success-message">
      <% with $Fields.dataFieldByName('Comment') %>
        $Field
      <% end_with %>
      <div class="input-group-btn">
        <% loop $Actions %>
          $Field
        <% end_loop %>
      </div>
    </div>
  </div>
  $Fields.dataFieldByName('FAQID')
  $Fields.dataFieldByName('SecurityID')
</form>