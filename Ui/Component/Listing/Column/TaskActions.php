<?php
namespace Vvc\Task\Ui\Component\Listing\Column;
 
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
 
class TaskActions extends Column
{
  /** Url path */
  const GRID_URL_PATH_EDIT = 'vvc_task/managetask/edit';
  const GRID_URL_PATH_DELETE = 'vvc_task/managetask/delete';
 
  /** @var UrlInterface */
  protected $urlBuilder;
 
  /**
  * @var string
  */
  private $editUrl;
 
  /**
  * @param ContextInterface $context
  * @param UiComponentFactory $uiComponentFactory
  * @param UrlInterface $urlBuilder
  * @param array $components
  * @param array $data
  * @param string $editUrl
  */
  public function __construct(
      ContextInterface $context,
      UiComponentFactory $uiComponentFactory,
      UrlInterface $urlBuilder,
      array $components = [],
      array $data = [],
      $editUrl = self::GRID_URL_PATH_EDIT
      ) {
            $this->urlBuilder = $urlBuilder;
            $this->editUrl = $editUrl;
            parent::__construct($context, $uiComponentFactory, $components, $data);
        }
 
  /**
  * Prepare Data Source
  *
  * @param array $dataSource
  * @return array
  */
  public function prepareDataSource(array $dataSource)
  {
    if (isset($dataSource['data']['items'])) {
        foreach ($dataSource['data']['items'] as & $item) {
            $name = $this->getData('name');
            if (isset($item['task_id'])) {
              $item[$name]['edit'] = [
                  'href' => $this->urlBuilder->getUrl($this->editUrl, ['task_id' => $item['task_id']]),
                  'label' => __('Edit')
              ];
              $item[$name]['delete'] = [
                  'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_DELETE, ['task_id' => $item['task_id']]),
                  'label' => __('Delete'),
                  'confirm' => [
                  'title' => __('Delete "${ $.$data.task_name }"'),
                  'message' => __('Are you sure you wan\'t to delete a "${ $.$data.task_name }" record?')
                  ]
              ];
          }
        }
    }
  return $dataSource;
  }
}