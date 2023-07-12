<?php

declare(strict_types=1);
error_reporting(-1);
mb_internal_encoding('utf-8');



class Department
{
    private string $name;                             // Имя департамента
    private array  $peopleInDepartment;               // Люди в департаменте

    public function __construct($name, array $peopleInDepartment)
    {
        $this->name = $name;
        $this->peopleInDepartment = $peopleInDepartment;
    }

    // Колнирование сотрудников при клонировании обьекта

    public function __clone(): void
    {
        foreach($this->peopleInDepartment as &$employee) {
            $employee = clone $employee;
        }
    }

    // Возвращает имя департамента

    public function getDepartmentName(): string
    {
        return $this->name;
    }

    // Считает количество работников в департаменте

    public function getCountEmployees(): int
    {
        return count($this->peopleInDepartment);
    }

    // Выводит массив со всеми сотрудниками

    public function getPeopleInDepartment(): array
    {
        return $this->peopleInDepartment;
    }

    // Считает всю зарплату которую необходимо выплатить департаменту

    public function getCostSalaryDepartment(): float
    {
        $result = array_reduce($this->peopleInDepartment, function($sumSalary, $employee) {
            $sumSalary += $employee->getSalary();
            return $sumSalary;
        });
        return $result;
    }

    // Считает все кофе выпитое департаментом

    public function getCostCoffeeDepartment(): float
    {
        $result = array_reduce($this->peopleInDepartment, function($sumCoffee, $employee) {
            $sumCoffee += $employee->getCoffeeCount();
            return $sumCoffee;
        });
        return $result;
    }

    // Считает все документы произведенные департаментом

    public function getCountAllDocumentsDepartment(): float
    {
        $result = array_reduce($this->peopleInDepartment, function($sumDocuments, $employee) {
            $sumDocuments += $employee->getDocumentCount();
            return $sumDocuments;
        });
        return $result;
    }

    // Выводим всех людей департамента работающих по определенной профессии

    public function getProfessionInDepartment(string $profession): array
    {
        $result = array_filter($this->peopleInDepartment, function ($employee) use ($profession) {
            return $employee->getEmployeeProfession() === $profession;
        });
        return $result;
    }

    // Сортирует сотрудников по возрастанию ранга

    public function sortRankEmployees()
    {
        usort($this->peopleInDepartment, function($employee, $compareEmployee) {
            return $employee->getRank() - $compareEmployee->getRank();
        });
    }

    // Выбирает превые $count сотрудников из департамента

    public function getEmployees(int $count): array
    {
        $result = array_slice($this->peopleInDepartment, 0, $count);
        return $result;
    }

    // Удалить сотрудников из департамента

    /**
     * массив $employyes с выбранными сотрудниками с помощью функции getEmployees
     */

    public function removeEmployees(array $employees): void
    {
        foreach ($this->peopleInDepartment as $key => $employee) {
            foreach($employees as $removeEmployee) {
                if ($employee === $removeEmployee) {
                    unset($this->peopleInDepartment[$key]);
                    break;
                }
            }
        }
    }

}



class Employee
{
    private string $profession;                         // Профессия
    private int	 $rank;                            	  // Ранг сотрудника (1, 2, 3 и т.д.)
    private float	 $baseRate;                        	  // Базовая ставка
    private bool	 $isBoss;                             // Является ли начальником (true / false)
    private float	 $coffee;                          	  // Сколько выпивает литров кофе
    private int	 $documents;                       	  // Сколько производит отчетов, чертежей, исследований

    public function __construct(string $profession, int $rank, float $baseRate, bool $isBoss, float $coffee, int $documents)
    {

        $this->profession = $profession;
        $this->rank = $rank;
        $this->baseRate = $baseRate;
        $this->isBoss = $isBoss;
        $this->coffee = $coffee;
        $this->documents = $documents;
    }

    // Получить название профессии сотрудника

    public function getEmployeeProfession(): string
    {
        return $this->profession;
    }

    // Рассчет зарплаты

    public function getSalary(): float
    {
        $result = 0;

        // Если начальник считаем з/п как начальнику, если работник - считаем как сотруднику

        if ($this->isBoss) {
            $result += $this->getBossSalary();
        } else {
            $result += $this->getEmployeeSalary();
        }
        return $result;
    }

    // Рассчет зарплаты для сотрудников

    public function getEmployeeSalary(): float
    {
        if ($this->rank === 1) {
            return $this->baseRate;
        } elseif ($this->rank === 2) {
            // Сотрудник второго ранга получает на 25% больше, чем первого
            return $this->baseRate * 1.25;
        } elseif ($this->rank === 3) {
            // Cотрудник 3-го ранга - на 50% больше, чем первого.
            return $this->baseRate * 1.50;
        }
    }

    // Рассчет зарплаты для руководителей

    public function getBossSalary(): float
    {
        // Руководитель получает на 50% больше, чем обычный сотрудник того же уровня
        return $this->getEmployeeSalary() * 1.5;
    }


    // Рассчет потребления кофе

    public function getCoffeeCount(): float
    {
        // Руководитель пьет в 2 раза больше кофе

        if ($this->isBoss) {
            return $this->coffee * 2;
        } else {
            return $this->coffee;
        }
    }

    // Получение результатов работы

    public function getDocumentCount(): float
    {
        // Руководитель не производит отчетов, чертежей или стратегических исследований

        if ($this->isBoss) {
            return 0;
        } else {
            return $this->documents;
        }

    }

    // Установка базовой ставки для сотрудника с определненной профессией

    public function setBaseRate(int $newBaseRate): float
    {
        return $this->baseRate = $newBaseRate;
    }

    // Установка выпитого коффе для сотрудника

    public function setCoffee(int $newCoffeeValue): float
    {
        return $this->coffee = $newCoffeeValue;
    }

    // Выводит является ли сотрудник начальников

    public function getBoss(): bool
    {
        return $this->isBoss;
    }

    // Присваивает сотруднику статус начальника

    public function setBoss(): bool
    {
        return $this->isBoss = true;
    }

    // Убирает у сотрудника статус начальника

    public function unsetBoss(): bool
    {
        return $this->isBoss = false;
    }

    // Узнаем ранк у сотрудника

    public function getRank(): int
    {
        return $this->rank;
    }

    // Устанавливаем ранг сотрудника

    public function setRank(int $newRank): int
    {
        return $this->rank = $newRank;
    }
}


// Функция добавления сотрудников

function addEmployee(string $profession, int $rank, int $baseRate, bool $isBoss, int $coffee, int $documents, int $countEmployee): array
{
    $result = [];
    for ($i = 0; $i < $countEmployee; $i++)
    {
        $result[] = new Employee($profession, $rank, $baseRate, $isBoss, $coffee, $documents);
    }
    return $result;
}

// Исходные данные по департаментам

$departmens = array(
    'Закупки' =>	new Department('Закупки', array_merge(addEmployee('Менеджер', 1, 500, false, 20, 200, 9),
            addEmployee('Менеджер', 2, 500, false, 20, 200, 3),
            addEmployee('Менеджер', 3, 500, false, 20, 200, 2),
            addEmployee('Маркетолог', 1, 400, false, 15, 150, 2),
            addEmployee('Менеджер', 2, 500, true, 20, 200, 1))
    ),
    'Продажи' =>	new Department('Продажи', array_merge(addEmployee('Менеджер', 1, 500, false, 20, 200, 12),
            addEmployee('Маркетолог', 1, 400, false, 15, 150, 6),
            addEmployee('Аналитик', 1, 800, false, 50, 5, 3),
            addEmployee('Аналитик', 2, 800, false, 50, 5, 2),
            addEmployee('Маркетолог', 2, 400, true, 15, 150, 1))
    ),
    'Реклама' =>	new Department('Реклама', array_merge(addEmployee('Маркетолог', 1, 400, false, 15, 150, 15),
            addEmployee('Маркетолог', 2, 400, false, 15, 150, 10),
            addEmployee('Менеджер', 1, 500, false, 20, 200, 8),
            addEmployee('Инженер', 1, 200, false, 5, 50, 2),
            addEmployee('Маркетолог', 3, 400, true, 15, 150, 1))
    ),
    'Логистика' =>	new Department('Логистика', array_merge(addEmployee('Менеджер', 1, 500, false, 20, 200, 13),
            addEmployee('Менеджер', 2, 500, false, 20, 200, 5),
            addEmployee('Инженер', 1, 200, false, 5, 50, 5),
            addEmployee('Менеджер', 1, 500, true, 20, 200, 1))
    )
);



function padRight(string|int|float $columnName, $columnWidth): string
{
    $result = '';
    $stringLength = mb_strlen((string)$columnName);

    if ($stringLength < $columnWidth) {
        $result .= $columnName;
        $result .= str_repeat(' ', $columnWidth - $stringLength);
    } else {
        $result .= $columnName;
    }

    return $result;
}

function padLeft(string|int|float $columnName,int $columnWidth): string
{
    $result = ' ';
    $stringLength = mb_strlen((string)$columnName);

    if ($stringLength < $columnWidth) {
        $result .= str_repeat(' ', $columnWidth - $stringLength);
        $result .= $columnName;
    } else {
        $result .= str_repeat(' ', $columnWidth / 2);
        $result .= $columnName;
    }

    return $result;
}


function createTable(array $departmens): void
{

    // Ширина колонок

    $col1 = 15;
    $col2 = 8;
    $col3 = 8;
    $col4 = 8;
    $col5 = 8;
    $col6 = 8;
    // $col 7 для последней колонки тугр / стр чтобы показатели прижать к правому краю
    $col7 = 16;

    // Заголовок таблицы

    echo padRight("Департамент", $col1) .
        padLeft("Сотр.", $col2) .
        padLeft("Тугр.", $col3) .
        padLeft("Кофе", $col4) .
        padLeft("Стр.", $col5) .
        padLeft("Тугр. / Стр.", $col6) . "\n";

    // Разделительная линия

    echo str_repeat('-', 70) . "\n";

    foreach ($departmens as $department) {
        echo padRight($department->getDepartmentName(), $col1) .
            padLeft($department->getCountEmployees(), $col2) .
            padLeft($department->getCostSalaryDepartment(), $col3) .
            padLeft($department->getCostCoffeeDepartment(), $col4) .
            padLeft($department->getCountAllDocumentsDepartment(), $col5) .
            padLeft(round($department->getCostSalaryDepartment() / $department->getCountAllDocumentsDepartment(), 1), $col7) .  "\n";

    }

    // Считаем сколько всего

    $totalEmployees = [];
    $totalSalary = [];
    $totalCoffee = [];
    $totalDocuments = [];
    $totalSalaryPerDocuments = [];

    foreach ($departmens as $department) {
        $totalEmployees[] = $department->getCountEmployees();
        $totalSalary[] = $department->getCostSalaryDepartment();
        $totalCoffee[] = $department->getCostCoffeeDepartment();
        $totalDocuments[] = $department->getCountAllDocumentsDepartment();
        $totalSalaryPerDocuments[] = round($department->getCostSalaryDepartment() / $department->getCountAllDocumentsDepartment(), 1);
    }

    // Выводим сначала средние показатели

    echo padRight("Среднее", $col1) .
        padLeft(round(array_sum($totalEmployees) / count($totalEmployees), 1), $col2) .
        padLeft(round(array_sum($totalSalary) / count($totalEmployees), 1), $col3) .
        padLeft(round(array_sum($totalCoffee) / count($totalEmployees), 1), $col4) .
        padLeft(round(array_sum($totalDocuments) / count($totalEmployees), 1), $col5) .
        padLeft(round(array_sum($totalSalaryPerDocuments) / count($totalEmployees), 1), $col7) . "\n";


    // Выводим сколько всего

    echo padRight("Всего", $col1) .
        padLeft(array_sum($totalEmployees), $col2) .
        padLeft(array_sum($totalSalary), $col3) .
        padLeft(array_sum($totalCoffee), $col4) .
        padLeft(array_sum($totalDocuments), $col5) .
        padLeft(array_sum($totalSalaryPerDocuments), $col7) . "\n\n";
}

echo "Стартовые значения \n\n";

createTable($departmens);


// Функция для клонирования департаментов чтобы не изменять исходные данные

/**
 * $arrayWithDepartmens - массив с департаментами в котором содежатся сотрудники
 */

function cloneDepartments($arrayWithDepartmens): array
{
    $result = [];
    foreach($arrayWithDepartmens as $departmentName => $department) {
        $result[$departmentName] = clone $department;

    }
    return $result;
}


// Антикризисное решение № 1 Сократить в каждом департаменте 40% (округляя в большую сторону) инженеров,
// преимущественно самого низкого ранга. Если инженер является боссом, вместо него надо уволить другого инженера,
// не босса.

function cutEmployees(int $percentReduction, string $professionReduction, array $departmensWithAllEmployees): array
{
    // Получаем работников нужной профессии в каждом департаменте

    $departmensWithProfession = [];

    foreach ($departmensWithAllEmployees as &$department) {

        $departmensWithProfession[$department->getDepartmentName()] = new Department($department->getDepartmentName(), $department->getProfessionInDepartment($professionReduction));
    }

    // Проходимся по каждому департаменту и сортируем сотрудников с требуемой профессией по рангу, по возрастанию

    foreach ($departmensWithProfession as &$department) {
        $department->sortRankEmployees();
    }

    // Проходимся по каждому департаменту уже с осортированными сотрудниками

    foreach ($departmensWithProfession as &$department) {

        // Переводим проценты в количество людей для каждого департамента

        $countEmployees = (int) ceil(count($department->getPeopleInDepartment()) * ($percentReduction / 100));

        // Отбираем сотрудников для увольнения

        $reduceEmployees = $department->getEmployees($countEmployees);

        // Увольняем

        foreach ($departmensWithAllEmployees as &$department) {
            $department->removeEmployees($reduceEmployees);
        }
    }
    echo "Антикризисное решение №1 сократить профессию {$professionReduction} на {$percentReduction}% \n\n";
    return $departmensWithAllEmployees;
}

// Процент сотрудников который нужно скоратить

$percentReduction = 40;

// Название профессии где произвести сокращение

$professionReduction = 'Инженер';

// Клонируем исходные данные

$departmensAnticrisis1 = cloneDepartments($departmens);

// Сокращаем сотрудников используя функцию

$departmensAnticrisis1 = cutEmployees($percentReduction, $professionReduction, $departmensAnticrisis1);

// Выводим в таблицу

createTable($departmensAnticrisis1);