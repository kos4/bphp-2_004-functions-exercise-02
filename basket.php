<?php

declare(strict_types = 1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
  OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
  OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
  OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
  OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];


do {
  system('clear');
  //    system('cls'); // windows

  $operationNumber = getOperationNumber($operations, $items);
  echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

  switch ($operationNumber) {
    case OPERATION_ADD:
      addProductBasket($items);

      break;
    case OPERATION_DELETE:
      deleteProductBasket($items);

      break;
    case OPERATION_PRINT:
      printBasket($items);

      break;
  }

  echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;

function viewBasketItems(array $items, int $operation = null): void
{
  if (count($items)) {
    if ($operation === OPERATION_DELETE) {
      echo 'Текущий список покупок:' . PHP_EOL;
      echo 'Список покупок: ' . PHP_EOL;
    } else {
      echo 'Ваш список покупок: ' . PHP_EOL;
    }

    echo implode("\n", $items) . "\n";

    if ($operation === OPERATION_PRINT) {
      echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    }
  } else {
    echo 'Ваш список покупок пуст.' . PHP_EOL;
  }
}

function getOperationNumber(array $operations, array $items): int
{
  do {
    viewBasketItems($items);

    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
    $operationNumber = (int) trim(fgets(STDIN));

    if (!array_key_exists($operationNumber, $operations)) {
      system('clear');

      echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
    }
  } while (!array_key_exists($operationNumber, $operations));

  return $operationNumber;
}

function addProductBasket(array &$items): void
{
  echo "Введение название товара для добавления в список: \n> ";
  $itemName = trim(fgets(STDIN));
  $items[] = $itemName;
}

function deleteProductBasket(array &$items): void
{
  // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
  viewBasketItems($items, OPERATION_DELETE);

  if (count($items) > 0) {
    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true) !== false) {
      while (($key = array_search($itemName, $items, true)) !== false) {
        unset($items[$key]);
      }
    }
  } else {
    pressEnterToContinue();
  }
}

function printBasket(array &$items): void
{
  viewBasketItems($items, OPERATION_PRINT);
  pressEnterToContinue();
}

function pressEnterToContinue(): void
{
  echo 'Нажмите enter для продолжения';
  fgets(STDIN);
}