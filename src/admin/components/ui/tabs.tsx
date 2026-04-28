import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"
import { Tabs as TabsPrimitive } from "radix-ui"

import { cn } from "@/lib/utils"

function Tabs({
  className,
  orientation = "horizontal",
  ...props
}: React.ComponentProps<typeof TabsPrimitive.Root>) {
  return (
    <TabsPrimitive.Root
      data-slot="tabs"
      data-orientation={orientation}
      orientation={orientation}
      className={cn(
        "apbl:group/tabs apbl:flex apbl:gap-2 apbl:data-[orientation=horizontal]:flex-col",
        className
      )}
      {...props}
    />
  )
}

const tabsListVariants = cva(
  "apbl:group/tabs-list apbl:inline-flex apbl:w-fit apbl:items-center apbl:justify-center apbl:rounded-lg apbl:p-[3px] apbl:text-muted-foreground apbl:group-data-[orientation=horizontal]/tabs:h-9 apbl:group-data-[orientation=vertical]/tabs:h-fit apbl:group-data-[orientation=vertical]/tabs:flex-col apbl:data-[variant=line]:rounded-none",
  {
    variants: {
      variant: {
        default: "apbl:bg-muted",
        line: "apbl:gap-1 apbl:bg-transparent",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  }
)

function TabsList({
  className,
  variant = "default",
  ...props
}: React.ComponentProps<typeof TabsPrimitive.List> &
  VariantProps<typeof tabsListVariants>) {
  return (
    <TabsPrimitive.List
      data-slot="tabs-list"
      data-variant={variant}
      className={cn(tabsListVariants({ variant }), className)}
      {...props}
    />
  )
}

function TabsTrigger({
  className,
  ...props
}: React.ComponentProps<typeof TabsPrimitive.Trigger>) {
  return (
    <TabsPrimitive.Trigger
      data-slot="tabs-trigger"
      className={cn(
        "apbl:relative apbl:inline-flex apbl:h-[calc(100%-1px)] apbl:flex-1 apbl:items-center apbl:justify-center apbl:gap-1.5 apbl:rounded-md apbl:border apbl:border-transparent apbl:px-2 apbl:py-1 apbl:text-sm apbl:font-medium apbl:whitespace-nowrap apbl:text-foreground/60 apbl:transition-all apbl:group-data-[orientation=vertical]/tabs:w-full apbl:group-data-[orientation=vertical]/tabs:justify-start apbl:hover:text-foreground apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50 apbl:focus-visible:outline-1 apbl:focus-visible:outline-ring apbl:disabled:pointer-events-none apbl:disabled:opacity-50 apbl:group-data-[variant=default]/tabs-list:data-[state=active]:shadow-sm apbl:group-data-[variant=line]/tabs-list:data-[state=active]:shadow-none apbl:dark:text-muted-foreground apbl:dark:hover:text-foreground apbl:[&_svg]:pointer-events-none apbl:[&_svg]:shrink-0 apbl:[&_svg:not([class*=size-])]:size-4",
        "apbl:group-data-[variant=line]/tabs-list:bg-transparent apbl:group-data-[variant=line]/tabs-list:data-[state=active]:bg-transparent apbl:dark:group-data-[variant=line]/tabs-list:data-[state=active]:border-transparent apbl:dark:group-data-[variant=line]/tabs-list:data-[state=active]:bg-transparent",
        "apbl:data-[state=active]:bg-background apbl:data-[state=active]:text-foreground apbl:dark:data-[state=active]:border-input apbl:dark:data-[state=active]:bg-input/30 apbl:dark:data-[state=active]:text-foreground",
        "apbl:after:absolute apbl:after:bg-foreground apbl:after:opacity-0 apbl:after:transition-opacity apbl:group-data-[orientation=horizontal]/tabs:after:inset-x-0 apbl:group-data-[orientation=horizontal]/tabs:after:bottom-[-5px] apbl:group-data-[orientation=horizontal]/tabs:after:h-0.5 apbl:group-data-[orientation=vertical]/tabs:after:inset-y-0 apbl:group-data-[orientation=vertical]/tabs:after:-right-1 apbl:group-data-[orientation=vertical]/tabs:after:w-0.5 apbl:group-data-[variant=line]/tabs-list:data-[state=active]:after:opacity-100",
        className
      )}
      {...props}
    />
  )
}

function TabsContent({
  className,
  ...props
}: React.ComponentProps<typeof TabsPrimitive.Content>) {
  return (
    <TabsPrimitive.Content
      data-slot="tabs-content"
      className={cn("apbl:flex-1 apbl:outline-none", className)}
      {...props}
    />
  )
}

export { Tabs, TabsList, TabsTrigger, TabsContent, tabsListVariants }
